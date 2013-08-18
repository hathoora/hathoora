<?php
namespace hathoora\database;

use hathoora\configure\config,
    hathoora\logger\profiler,
    hathoora\logger\logger;

/**
 * A db wrapper
 */
class db
{
    // constant for query reges
    const DB_QUERY_REGEXP = '/(\?)/';

    /**
     * Pool Name
     */
    protected $poolName;

    /**
     * Dsn Name
     */
    protected $dsnName;

    /**
     * for debugging
     */
    protected $dbName;

    /**
     * the query
     */
    protected $query;
    
    /**
     * the query args
     */
    protected $queryArgs;
    
    /**
     * query counter
     */
    protected $queryCounter = 0;
    
    /**
     * affected rows
     */
    protected $rowCount = 0;
    
    /**
     * last insert id
     */
    protected $lastInsertId = 0;
    
    /**
     * an array containg error stuff
     */
    protected $error;
    
    /**
     * A comment for query for debugging..
     */
    protected $comment;

    /**
     * dsn has onconnect sql commands to tun
     */
    protected $onConnectExecuting;

    /**
     * last dsn used
     */
    protected $lastDsnUsed;
    
    /**
     * User can specify which server to use on per usage basis
     */
    protected $userSpecifiedDsn;
    
    /**
     * db factory
     */
    protected $factory;
    
    /**
     * query results from factory
     */
    protected $queryResult;
    
    /**
     * Constructor which connects to db (factory)
     *
     * @param string
     *      poolName : which are defined in dbAdapter::$arrPools
     *      dsnName : which are defined in dbAdapter::$arrDsns @todo
     */
    public function __construct($poolName, $dsnName = null)
    {
        $this->poolName = $poolName; 
        $this->dsnName = $dsnName;
        
        return $this;
    }
    
    ##############################################################
    ##
    ##   DSN/Debug stuff
    ## 
    /**
     * From the pool, get a dsn to connect to based on read/write logic & weights
     *
     * @param string $type read or write 
     */
    private function setDsnFactory($type)
    {
        static $arrPoolCurrentDsnForType;
        $conn = $dbDsnName = $arrAvailableDsn = null;
        $currentPoolDsnTypeIdentifier = $this->poolName .':' . $type;
        $userSpecifiedDsn = $this->userSpecifiedDsn;
        
        if (is_array($userSpecifiedDsn))
        {
            $arrAvailableDsn = $userSpecifiedDsn;
        }
        // from static var
        else if (isset($arrPoolCurrentDsnForType[$currentPoolDsnTypeIdentifier]))
        {
            $arrAvailableDsn = $arrPoolCurrentDsnForType[$currentPoolDsnTypeIdentifier];
        }
        // figure out
        else if (isset(dbAdapter::$arrPools[$this->poolName]))
        {
            $type = strtolower($type);
            $arrAvailableDsn = $this->getAvailableDsn($type);
            $arrPoolCurrentDsnForType[$currentPoolDsnTypeIdentifier] = $arrAvailableDsn;
        }
        
        if (is_array($arrAvailableDsn))
        {
            $uniqueDsnName = isset($arrAvailableDsn['uniqueDsnName']) ? $arrAvailableDsn['uniqueDsnName'] : null;
            $dbDsnName = isset($arrAvailableDsn['name']) ? $arrAvailableDsn['name'] : null;
            $this->lastDsnUsed = $arrAvailableDsn;
            
            if ($uniqueDsnName && isset(dbAdapter::$arrDsns[$uniqueDsnName]))
            {
                $arrDsn =& dbAdapter::$arrDsns[$uniqueDsnName];
                $conn = isset($arrDsn['conn']) ? $arrDsn['conn'] : null;
                $onConnect = isset($arrDsn['on_connect']) ? $arrDsn['on_connect'] : null;
                
                // any commands to run on connect?
                if ($conn && is_array($onConnect) && !isset($arrDsn['on_connect_executed']))
                {
                    // so that we do initialize during query to lookup for dsn and get in recursive loop
                    // and some extra work to make onconnect queries work with debugging..
                    $this->onConnectExecuting = true; 
                    $this->factory = $conn;
                    $this->dbName = $this->poolName . ($dbDsnName ? '/'. $dbDsnName : null);
                    $this->initializeDebug('On connect queries...');
                    
                    foreach($onConnect as $onConnectQuery)
                    {
                        $this->query($onConnectQuery);
                    }
                    
                    $arrDsn['on_connect_executed'] = true;
                    $this->onConnectExecuting = false;
                }                  
            }
        }
        
        $this->dbName = $this->poolName . ($dbDsnName ? '/'. $dbDsnName : null);
        $this->factory = $conn;
    }

    /**
     * From given set of servers returns the next available server
     *
     * @param $type
     * @return array|null
     */
    private function getAvailableDsn($type)
    {
        $arrAvailableDsn = null;
        $type = strtolower($type);
        
        if (($arrPool = dbAdapter::$arrPools[$this->poolName]) && isset($arrPool['servers']) && is_array($arrPool['servers']))
        {
            $arrTypeServers = $arrPool['servers'][$type];

            foreach($arrTypeServers as $i => $arrTypeServer)
            {
                $arrAvailableDsn = $this->getAvailableDsnConnector($arrTypeServer);
                if (is_array($arrAvailableDsn))
                    break;
            }
        }
        
        return $arrAvailableDsn;
    }
    
    /**
     * Helper for getAvailableDsn
     */
    private function getAvailableDsnConnector(&$arrTypeServer)
    {
        $arrAvailableDsn = null;
        $dsn = $arrTypeServer['dsn'];
        $options = isset($arrTypeServer['options']) ? $arrTypeServer['options'] : null;
        $onConnect = isset($arrTypeServer['on_connect']) ? $arrTypeServer['on_connect'] : null;
        $name = isset($arrTypeServer['name']) ? $arrTypeServer['name'] : null;
        $uniqueDsnName = isset($arrTypeServer['uniqueDsnName']) ? $arrTypeServer['uniqueDsnName'] : null;
        
        $arrDsn =& dbAdapter::$arrDsns[$uniqueDsnName];
        $dsnStatus = $arrDsn['status'];
        if ($dsnStatus == 'connected')
        {
            $arrAvailableDsn = array(
                                    'uniqueDsnName' => $uniqueDsnName,
                                    'name' => $name);            
        }
        else if ($dsnStatus == 'not connected')
        {
            if (preg_match('/^(\w+):\/\/(\w+):(|\w+)@(.+?):(\d+)\/(.+?)$/', $dsn, $arrMatch))
            {
                $engine = $arrMatch['1'];
                $host = $arrMatch['4'];
                $port = $arrMatch['5'];
                $user = $arrMatch['2'];
                $password = $arrMatch['3'];
                $schema = $arrMatch['6'];
                $socket = null;
                
                // @todo: handle more db drivers
                if ($engine == 'mysql')
                {
                    try
                    {
                        //echo "Trying to connect to $name -> $uniqueDsnName ";
                        $arrDsn['conn'] = new dbMysqli($host, $user, $password, $schema, $port, $socket, $options);
                        $arrDsn['status'] = 'connected';
                        $arrAvailableDsn = array(
                                                'uniqueDsnName' => $uniqueDsnName,
                                                'name' => $name);

                        logger::log(logger::LEVEL_INFO, 'mySQL connected to '. $this->poolName . ($name ? '/'. $name : null));
                    }
                    catch (\Exception $e)
                    {
                        //echo ": FAILED";
                        $error = $e->getMessage();
                        $arrDsn['status'] = 'cannot connect';
                        
                        logger::log(logger::LEVEL_ERROR, 'mySQL connection error for '. $this->poolName . ($name ? '/'. $name : null) .': '. $error);
                    }
                    
                    //echo "<br/>";
                }
            }
        }
        
        return $arrAvailableDsn;
    }
    
    /**
     * use a particular dsn
     *
     */
    public function server($string)
    {
        $arrPool = dbAdapter::$arrPools[$this->poolName];
        $arrTypeToRole = array(
                                'master' => 'write',
                                'slave' => 'read');
        $userSpecifiedDsn = array();
        $server = $string;
        
        if ($string == 'last')
        {
            $userSpecifiedDsn = $this->lastDsnUsed;
        }
        else if (preg_match('/(master|slave):(.*)/i', $string, $arrMatch))
        {
            $role = strtolower($arrMatch[1]);
            if (isset($arrTypeToRole[$role]))
            {
                $type = $arrTypeToRole[$role];
                $dsnName = $arrMatch[2];
                $userSpecifiedDsn['dsnName'] = $dsnName;
                $server = $dsnName;
                if (isset($arrPool['servers']) && isset($arrPool['servers_role_weight_mapping']) && isset($arrPool['servers'][$type]) &&
                    isset($arrPool['servers_role_weight_mapping'][$dsnName]) && isset($arrPool['servers_role_weight_mapping'][$dsnName][$role]))
                {
                    $dsnWeightName = $arrPool['servers_role_weight_mapping'][$dsnName][$role];
                    if (isset($arrPool['servers'][$type][$dsnWeightName]))
                    {
                        $arrTypeServer =& $arrPool['servers'][$type][$dsnWeightName];
                        $userSpecifiedDsn = $this->getAvailableDsnConnector($arrTypeServer);
                    }
                }
            }
        }
        
        if (is_array($userSpecifiedDsn))
        {
            $this->error = array(
                                    'number' => -1,
                                    'message' => 'Unable to connect to ' . $string. '. Make sure the name matches the one specified in configuration.');
        }
        
        $this->userSpecifiedDsn = $userSpecifiedDsn;
        
        return $this;
    }
    
    /**
     * Sets a comment for the debugging query
     */
    public function comment($comment)
    {
        $this->comment = $comment;
        
        return $this;
    }
    
    ##############################################################
    ##
    ##   Factory operations
    ##    
    /**
     * Initialize function which sets debug & factory
     *
     * @param string $queryType write|read to select appropriate factory
     * @param string $comment for debugging
     */
    private function initialize($queryType, $comment = null)
    {
        $this->setDsnFactory($queryType);
        $this->initializeDebug($comment = null);
    }
    
    /**
     * initialize debugging
     */
    private function initializeDebug($comment = null)
    {
        // for debugging
        if (config::get('hathoora.logger.profiling'))
        {
            $this->arrDebug = array();
            $this->arrDebug['dsn_name'] = $this->dbName;
            $this->arrDebug['start'] = microtime();
            if ($this->comment)
                $comment = $this->comment;
            $this->arrDebug['comment'] = $comment;
        }    
    }

    /**
     * This function does bunch of stuff:
     *      assign $this->rowCount
     *      assign $this->lastInsertId
     *      check if factory occured any errors
     *          return true false or throw exception
     *
     * @param bool $returnStatus, when true then we don't throw exception upon errors
     * @throws \Exception
     * @return bool when returnStatus = true, then returns true when no errors, false when errors
     */
    private function finalize($returnStatus = false)
    {   
        // reset dsn & comment so we pick per query bases
        $this->userSpecifiedDsn = $this->comment = null;
        
        if (is_object($this->factory))
            $this->error = $this->factory->getError();
        else if (!is_array($this->error))
        {
            $this->error = array(
                                    'number' => -1,
                                    'message' => 'Unable to connect.');
        }
        
        $hasError = false;
        if (isset($this->error['number']))
            $hasError = true;
        
        if (config::get('hathoora.logger.profiling') && ($this->query || !empty($this->arrDebug['comment'])))
        {
            $this->arrDebug['end_query'] = microtime();
            $this->arrDebug['query'] = $this->query;
            
            if (!$this->arrDebug['query'] && !empty($this->arrDebug['comment']))
                $this->arrDebug['query'] = $this->arrDebug['comment'];
            
            if ($hasError)
                $this->arrDebug['error'] = $this->error['message'];
                
            profiler::profile('db', $this->poolName . $this->queryCounter, $this->arrDebug);
        }

        $this->rowCount = $this->lastInsertId = null;
        if (is_object($this->factory))
        {
            $this->rowCount = $this->factory->getAffectedRows();
            $this->lastInsertId = $this->factory->getlastInsertId();
        }
        
        if ($hasError)
        {
            $this->errorfactory();
            if (!$returnStatus)
                throw new \Exception($this->error['message'], $this->error['number']); 
            else
                return false;
        }
        
        return true;
    }
    
    /**
     * Error factory
     */
    private function errorfactory()
    {
        // log this error
        logger::log(logger::LEVEL_ERROR, 'SQL Error ('. $this->error['number'] .') '. $this->error['message'] .'<br/><br/>'. $this->query);
    }

    /**
     * Returns db factory
     */
    public function getFactory()
    {
        return $this->factory;
    }
    
    /**
     * Get SQL error
     *
     * @return array of error
     */
    public function getError()
    {
        return $this->error;
    }


    
    ##############################################################
    ##
    ##   Query stuff
    ##
    /**
     * Query args replacements (drupal inspired)
     * 
     * This has to be static..
     */
    public static function queryArgsReplace($match, &$factory = false) 
    {
        static $args = NULL;
        static $objEscapeFactory; // since its a static function we need to get db factory from
        if (is_object($factory))
        {
            $args = $match;
            $objEscapeFactory = $factory;
            return;
        }
        
        switch ($match[1]) 
        {
            case '?':
            {
                if (is_array($args))
                    return $objEscapeFactory->escape(array_shift($args));
                return '?';
            }
        }
    }

    /**
     * query (or execute) function
     *
     * @param string $query
     * @param array $args (optional)
     * @param bool $isMultiQuery
     * @return mixed|object dbResult object successful, else returns false
     */
    public function query($query, $args = null, $isMultiQuery = false)
    {
        $this->queryArgs = $args;
        
        if (preg_match('/^s{0,}SELECT/im', $query))
            $queryType = 'read';
        else
            $queryType = 'write';
        
        if (empty($this->onConnectExecuting))
            $this->initialize($queryType);

        if (is_array($args) && count($args))
        {
            $this->queryArgsReplace($args, $this);
            $query = preg_replace_callback(self::DB_QUERY_REGEXP, '\hathoora\database\db::queryArgsReplace', $query);
        }
        $this->query = $query;
        $this->queryCounter++;
        
        // log it
        logger::log(logger::LEVEL_INFO, $query);
        
        if (is_object($this->factory))
        {
            if (!$isMultiQuery)
                $this->queryResult = $this->factory->query($query);
            else
                $this->queryResult = $this->factory->multiQuery($query);
        }
            
        $hasNoErrors = $this->finalize(false);
        if ($hasNoErrors)
            return new dbResult($this->factory, $this->queryResult);
        
        return false;
    }
    
    /**
     * Runs a multi query
     */
    public function multiQuery($query, $args = false)
    {
        $arrResult = null;
        $this->query($query, $args, true);
        
        // @todo do this at adapter level
        if ($this->queryResult) 
        {
            do 
            {
                if ($result = $this->factory->store_result()) 
                { 
                    while($row = $result->fetch_row()) 
                    {
                        $arrResult[] =$row;
                    }
                    $result->close();
                }
            } while($this->factory->next_result());
        }
        
        return $arrResult;
    }
    
    /**
     * Escape string
     */
    public function escape($string)
    {
        $escapedString = null;
        $this->initialize('read');
        
        if (is_object($this->factory))
            $escapedString = $this->factory->quote($string);
            
        $this->finalize(false);
        
        return $escapedString;
    }
    
    /**
     * Begins transaction by setting autocommit to off
     */
    public function beginTransaction()
    {
        $this->initialize('write', 'BEGIN TRANSACTION');
        
        if (is_object($this->factory))
            $this->factory->beginTransaction();
            
        $this->finalize(true);            
    }
    
    /**
     * Commits the query 
     */
    public function commit()
    {
        $dbResponse = null;
        $this->initialize('write', 'COMMIT');
        
        if (is_object($this->factory))
            $dbResponse = $this->factory->commit();
            
        $this->finalize();
        
        return $dbResponse;
    }
    
    /**
     * Function to rollback
     */
    public function rollback()
    {
        $dbResponse = null;
        $this->initialize('write', 'ROLLBACK');
        
        if (is_object($this->factory))
            $dbResponse = $this->factory->rollback();
            
        $this->finalize();
        
        return $dbResponse;            
    }

    
    
    ##############################################################
    ##
    ##   Common query operation shortcuts
    ##    
    /**
     * query (or execute) function
     *
     * @param string $query
     * @param array $args (optional)
     * @return int last insert id when successful, else returns false
     */
    public function insert($query, $args = null)
    {
        $result = $this->query($query, $args);
        if ($result && $this->rowCount)
            $return = $this->lastInsertId;
        else
            $return = false;
            
        profiler::modify('db', $this->poolName . $this->queryCounter, array('end_execution' => microtime()));
            
        return $return;
    }
    
    /**
     * fetch coulmn fetch for a single row
     *
     * @param string $query
     * @param array $args (optional)
     * @return array of row
     */
    public function fetchValue($query, $args = false)
    {
        $result = $this->query($query, $args);
        if ($result && $result->rowCount())
            $arrResult = @array_pop($result->fetchArray());
        else
            $arrResult = false;
            
        // for debugging, we need to keep track SQL exection and total time 
        profiler::modify('db', $this->poolName . $this->queryCounter, array('end_execution' => microtime()));

        return $arrResult;
    }    
    
    /**
     * fetch single row
     *
     * @param string $query
     * @param array $args (optional)
     * @return array of row
     */
    public function fetchArray($query, $args = false)
    {
        $result = $this->query($query, $args);
        if ($result && $result->rowCount())
            $arrResult = $result->fetchArray();
        else
            $arrResult = false;
            
        // for debugging, we need to keep track SQL exection and total time 
        profiler::modify('db', $this->poolName . $this->queryCounter, array('end_execution' => microtime()));

        return $arrResult;
    }

    /**
     * fetch all rows
     *
     * @param string $query
     * @param array $args (optional)
     * @param array $arrExtra for extra logic
     *      - pk: field name to return array keyed with this value from results set
     * @return array of row
     */
    public function fetchArrayAll($query, $args = null, $arrExtra = array())
    {
        $arrResult = null;
        $result = $this->query($query, $args);
        if ($result && $result->rowCount())
        {
            while ($row = $result->fetchArrayAll())
            {
                if (isset($arrExtra['pk']) && $row[$arrExtra['pk']])
                    $arrResult[$row[$arrExtra['pk']]] = $row;
                else
                    $arrResult[] = $row;
            }
        }
        else
            $arrResult = false;
        
        // for debugging, we need to keep track SQL exection and total time 
        profiler::modify('db', $this->poolName . $this->queryCounter, array('end_execution' => microtime()));
            
        return $arrResult;
    }
    
    /**
     * upon destruct close factory connection
     */
    public function __destruct()
    {
        if (is_object($this->factory))
            $this->factory->disconnect();
    }
}