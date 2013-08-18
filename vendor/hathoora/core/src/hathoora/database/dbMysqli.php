<?php
namespace hathoora\database;

/**
 * Mysqli DB
 */
class dbMysqli extends \Mysqli implements dbInterface
{
    /**
     * Connects to db
     *
     * @return mysqli object
     * @throws exception when unable to connect
     */
     public function __construct($host = NULL, $username = NULL, $passwd = NULL, $dbname = NULL, $port = NULL, $socket = NULL, $options = NULL)
     {
        $this->host = $host;
        $this->user = $username;
        $this->password = $passwd;
        $this->schema = $dbname;
        $this->port = $port;
        if (!$this->port)
            $this->port = 3306;

        @parent::__construct($this->host, $this->user, $this->password, $this->schema, $this->port, $socket = NULL);
        if ($this->connect_error)
            throw new \Exception('Connect Error (' . $this->connect_errno . ') ' . $this->connect_error); 
        
        // any options to set
        if (is_array($options))
        {
            foreach($options as $option => $value)
            {
                if (defined($option))
                    parent::options(constant($option), $value);
            }
        }
            
        return $this;
    }
    
    /**
     * Disconnect dbs
     */
    public function disconnect()
    {
        @parent::close();
    }
    
    /**
     * Escape for sql injection
     * PDO's escape functions add a 'around the string', this function removes that and makes it
     * work like mysql_real_escape_string
     *
     * @param string $string
     * @return escaped string
     */
    public function quote($string)
    {
        return $this->real_escape_string($string);  
    }
    
    /**
     * query (or execute) function
     *
     * @param string $query
     */
    public function query($query)
    {
        return parent::query($query);
    }
    
    /**
     * multi query (or execute) function
     *
     * @param string $query
     */
    public function multiQuery($query)
    {
        return parent::multi_query($query);
    }
    
    /**
     * Begins transaction by setting autocommit to off
     */
    public function beginTransaction()
    {
        parent::autocommit(false);
    }
    
    /**
     * Commits the query
     */
    public function commit()
    {
        $status = parent::commit();
        parent::autocommit(true); // reset
        
        return $status;
    }
    
    /**
     * Function to rollback
     */
    public function rollback()
    {
        $rollBack = parent::rollback();
        parent::autocommit(true); // reset
        
        return $rollBack;
    }
    
    /**
     * fetch a result row as an associative
     *
     * @param object $result mysqli_result from parent::query()
     */
    public function fetchArray($result = false)
    {
        if (is_object($result) && $result instanceof \mysqli_result)
            return $result->fetch_array(MYSQLI_ASSOC);
        return false;
    }
    
    /**
     * Fetch all, works only with php 5.3
     *
     * @param object $result mysqli_result from parent::query()
     * 
     */
    public function fetchArrayAll($result = false)
    {
        if (is_object($result) && $result instanceof \mysqli_result)
            return $result->fetch_assoc();
            //return $result->fetch_all(MYSQLI_ASSOC);
        return false;    
    }
    
    /**
     * Returns affected rows
     */
    public function getAffectedRows()
    {
        return $this->affected_rows;
    }
    
    /**
     * Returns last insert id
     */
    public function getlastInsertId()
    {
        return $this->insert_id;
    }
    
    /**
     * a function that must return an array containg:
     *      number, 
     *      message 
     * whenever there is an error
     * else return false when no errors
     */
    public function getError()
    {
        if ($this->error)
        {
            return array(
                'number' => $this->connect_errno  ? $this->connect_errno : $this->errno,
                'message' => $this->connect_error ? $this->connect_error : $this->error
            );
        }
        return false;
    }
}