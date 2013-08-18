<?php
namespace hathoora\model;

use hathoora\container,
    hathoora\database\dbAdapter;

class model extends container
{
    public function __construct() { }
    
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
}