<?php
namespace hathoora\model;

use hathoora\container,
    hathoora\database\dbAdapter,
    hathoora\grid\grid;

/**
 * Simple Active record class
 */
class modelSAR extends container
{
    private $arrReservedProperties = array(
        '_tableName' => 1,
        '_primaryKey' => 1,
        '_fields' => 1
    );
    
    /**
     * constructor
     * 
     * @param mixed
     *      if array then we assign properties to the object
     */
    public function __construct($args = null) 
    {
        if (is_array($args))
        {
            foreach ($args as $k => $v)
            {
                $this->$k = $v;
            }
        }
    }
    
    /**
     * Helper function for getting db connection
     * 
     * @param string $dsn_name defined in the config
     * @param bool $reBuild when true 
     * @return hathoora\database\db class
     */
    final public static function getDBConnection($dsn_name = 'default', $reBuild = false)
    {
        return dbAdapter::getConnection($dsn_name, $reBuild);
    }
    
    /**
     * Stores a new object in database
     * 
     * @param bool $dontExcludeEmptyProperties
     * @param bool $throwException throw exception on errors     
     * @return mixed
     *      - when $throwException = false and successful insert, it returns the ID of record created
     *        it also sets PK with ID
     *      - when $throwException = true, throws modelSARException upon error
     *      - 
     */
    final public function save($dontExcludeEmptyProperties = false, $throwException = false)
    {
        $sql = $this->getSARFieldsSetQuery($dontExcludeEmptyProperties, $throwException);
        $result = null;
        
        if ($sql)
        {
            $db = $this->getDBConnection();
            $tableName = $this->_tableName;
            $pk = $this->_primaryKey;
            
            $isAdd = false;
            if (empty($this->$pk))
                $isAdd = true;
            
            // build SQL
            if ($isAdd)
            {
                $sql = '
                INSERT INTO `'. $db->escape($tableName) .'`
                SET ' . $sql;
                $result = $db->insert($sql);
                $this->_hathoora_internal_save_type = true;
            }
            else
            {
                $sql = '
                UPDATE `'. $db->escape($tableName) .'`
                SET ' . $sql .'
                WHERE `'. $db->escape($pk) .'` = "'. $db->escape($this->$pk) .'" LIMIT 1';
                $result = $db->query($sql);            
                if ($result)
                    $result = $this->$pk;
            }
                
            if ($result)
            {
                $this->$pk = $result;

                // any post save hook?
                if (is_callable(array($this, 'postSave')))
                    $this->postSave();
            }

            if (!$result && $throwException)
            {
                $arrError = $db->getError();
                throw new modelSARException('SQL: '. $sql .'<br/><br/>'. $arrError['message'] . ' ['. $arrError['number'] .']');
            }
        }

        return $result;
    }
    
    /**
     * Converts an object to array
     * @param bool $dontExcludeEmptyProperties when true and an object property doesn't exist than don't use it
     * @param bool $includeNonObjectFields when true would also convert fields that are not assigned to $_fields array
     */    
    final public function toArray($dontExcludeEmptyProperties = true, $includeNonObjectFields = false)
    {
        return $this->getSARFields($dontExcludeEmptyProperties, $includeNonObjectFields);
    }

    /**
     * @param bool $dontExcludeEmptyProperties when true and an object property doesn't exist than don't use it
     * @param bool $includeNonObjectFields when true would also convert fields that are not assigned to $_fields array
     *
     * Returns DB fields of model
     */
    final public function getSARFields($dontExcludeEmptyProperties = true, $includeNonObjectFields = false)
    {
        $arrEntityFields = null;
        $arrFields = isset($this->_fields) ? $this->_fields : null;
        
        if ($includeNonObjectFields)
        {
            $cloneThis = clone $this;
            unset($cloneThis->arrReservedProperties);
            $arrEntityFields = (array) $cloneThis;
            foreach ($this->arrReservedProperties as $rProperty => $rValue)
            {
                if (isset($arrEntityFields[$rProperty]))
                    unset($arrEntityFields[$rProperty]);
            }
            
            if (is_array($arrEntityFields))
            {
                foreach($arrEntityFields as $field => $arrField)
                {
                    if (!is_array($arrEntityFields[$field]) && !mb_strlen($arrEntityFields[$field]) && $dontExcludeEmptyProperties == true)
                        unset($arrEntityFields[$field]);
                }
            }
        }
        else if (is_array($arrFields))
        {
            foreach ($arrFields as $field => $arrField)
            {
                if (!is_array($arrField))
                    $field = $arrField;
                    
                if (isset($this->$field))
                    $arrEntityFields[$field] = $this->$field;
                else if (!isset($this->$field) && $dontExcludeEmptyProperties != false)
                    $arrEntityFields[$field] = null;
            }
        }
        
        return $arrEntityFields;
    }
    
    /**
     * Returns the set query for obect
     *
     * @param bool $dontExcludeEmptyProperties when true and an object property doesn't exist than don't use it
     * @param bool $throwException throw exception on errors
     * @return string $sql with SETs only
     */
    final private function getSARFieldsSetQuery($dontExcludeEmptyProperties = false, $throwException = false)
    {
        $sql = $error = null;
        $db = $this->getDBConnection();
        
        // no db connection, throw exception
        if ($throwException && !$db)
            $error = 'Unable to get database connection.';
        
        if (($arrProperties = get_object_vars($this)) && $db && !$error)
        {
            // reservered properties are set?
            foreach ($this->arrReservedProperties as $rProperty => $rValue)
            {
                if (!isset($arrProperties[$rProperty]))
                {
                    if ($error) $error .= ', ';
                    $error .= $rProperty;
                }
            }
            if ($error)
                $error = 'SAR Reserved properties are missing in '. get_class($this) .': '. $error;            
            
            if (!$error)
            {
                $arrFields = $this->getSARFields($dontExcludeEmptyProperties);
                foreach ($arrFields as $field => $value)
                {
                    if ($sql) $sql .= ', ';
                    $sql .= '`'. $db->escape($field) .'` = "' . $db->escape($value) . '"';
                }
            }
        }
        
        // throw exception
        if ($throwException && $error)
            throw new modelSARException($error);

        return $sql;
    }
    
    /**
     * Returns the save type of entity that is being added
     */
    final public function getSaveType()
    {
        if (!empty($this->_hathoora_internal_save_type))
            return 'add';
        else
            return 'edit';
    }
    
    /**
     * magic function for accessing properties
     */
    final public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
    
    /**
     * magic function for setting properties
     */
    final public function __set($name, $value)
    {
        if (isset($this->arrReservedProperties[$name]))
            throw new modelSARException('You cannot overwrite reserved properties.');
        
        return $this->$name = $value;
    }
    
    /**
     * magic function for checking isset properties
     */
    final public function __isset($name)
    {
        return isset($this->$name);
    }

    /**
     * magic function for unsetting properties
     */
    final public function __unset($name)
    {
        if (isset($this->arrReservedProperties[$name]))
            throw new modelSARException('You cannot overwrite reserved properties.');    
        unset($this->$name);
    }    
    
    /**
     * magic function for accessing static methods
     */    
    final public static function __callStatic($name, $args)
    {  
        $db = self::getDBConnection();
        if (!$db)
            return false;

        if (substr($name, 0, 5) === 'fetch')
        {  
            $return = null;
            $class = get_called_class();
            $classObj = new $class;
            $table = $db->escape($classObj->_tableName);
            
            $arrParams = @array_pop($args);
            /**
             * we run a limited version of sqlRun()
             * @see \hathoora\grid\grid::sqlRun()
             */
            $selectField = null;
            if (isset($arrParams['selectField']))
                $selectField = grid::sqlBuildSelect($arrParams['selectField']);
            else
                $selectField = '*';
            
            $arrParams['skipTotal'] = true;
            $arrParams['queryRow'] = '
                SELECT '. $selectField .'
                FROM `'. $table .'`' .
                (isset($arrParams['joinRow']) ? grid::sqlBuildJoin($arrParams['joinRow']) : null) .
                (isset($arrParams['whereRow']) ? grid::sqlBuildWhere($arrParams['whereRow']) : null);
            $arrResult = grid::sqlRun($arrParams);
            
            if (is_array($arrResult))
            {
                if (count($arrResult) == 1)
                    $return = new $classObj(array_pop($arrResult));
                // @todo: support multiple results
            }
        }
        
        return $return;
    }
}