<?php
namespace hathoora\database;

/**
 * DB result class
 */
class dbResult
{
    protected $factory;
    protected $queryResult;

    /**
     * Constructor for db dependency injection
     */
    public function __construct(&$factory, &$queryResult)
    {
        $this->factory = $factory;
        $this->queryResult = $queryResult;
    }
    
    /**
     * fetch a result row as an associative
     */
    public function fetchArray()
    {
        return $this->factory->fetchArray($this->queryResult);
    }
    
    /**
     * fetch all results
     */
    public function fetchArrayAll()
    {
        return $this->factory->fetchArrayAll($this->queryResult);
    }
    
    /**
     * get affected rows
     */
    public function rowCount()
    {
        return $this->factory->getAffectedRows();
    }
    
    /**
     * get last insert id
     */
    public function lastInsertId()
    {
        return $this->db->getlastInsertId();
    }
}