<?php
namespace hathoora\database;

/**
 * An interface for db classes
 */
interface dbInterface
{
    /**
     * construct, throw exception if unable to connect
     *
     * @param array $arrConfig that contains dsn information
     */
    public function __construct($config);

    /**
     * Disconnect database connection
     */
    public function disconnect();
    
    /**
     * Escape for sql injection
     *
     * @param string $string
     * @return escaped string
     */
    public function quote($string);
    
    /**
     * Query function
     *
     * @param string $query
     * @param array $args (optional)
     */
    public function query($query);
    
    /**
     * Begins transaction by setting autocommit to off
     */
    public function beginTransaction();
    
    /**
     * Commits the query
     */
    public function commit();
    
    /**
     * Function to rollback
     */
    public function rollback();
    
    /**
     * fetch a result row as an associative array
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArray($result = false);
    
    /**
     * fetch all results as an associative
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArrayAll($result = false);    
    
    /**
     * Returns affected rows
     */
    public function getAffectedRows();
    
    /**
     * Returns last insert id
     */
    public function getlastInsertId();
    
    /**
     * a function that must return an array containg:
     *      number, 
     *      message 
     * whenever there is an error
     * else return false when no errors     
     */
    public function getError();  
}