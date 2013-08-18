<?php
namespace hathoora\logger;

use hathoora\configure\config;

class profiler
{
    /**
     * static variable for storing information
     */
    public static $arrProfile;
    
    /**
     * constructor
     */
    public function construct()
    {}
    
    /**
     * init function
     */
    public function init()
    {
        self::$arrProfile = array(
                                    'benchmark' => array(
                                        'Hathoora::ExecutionTime' => array(
                                            'start' => microtime()
                                        )
                                    ),
                                    'log' => array(),
                                    'db' => array(),
                                    'cache' => array());
    }

    /**
     * Simple benchmark function
     * @param string $name of benchmark
     * @internal param string $action (start or stop)
     * @return void
     */
    public function benchmark($name)
    {
        if (!config::get('hathoora.logger.profiling'))
            return null;
            
        $arr =& self::$arrProfile['benchmark'];

        if (!isset($arr[$name]))
            $arr[$name]['start'] = microtime();
        else
            $arr[$name]['end'] = microtime();  
    }

    /**
     * we want to debug various things categrozied by type debugging
     * 
     * @param string $type debugging type
     * @param string $name a unique identifier
     * @param array $arr stuff to debug (contains like start, end time etc..)
     * @return null
     */
    public static function profile($type, $name = null, $arr)
    {
        if (!config::get('hathoora.logger.profiling'))
            return null;
        
        if ($name)
            self::$arrProfile[$type][$name] = $arr;
        else
            self::$arrProfile[$type][] = $arr;
    }
    
    /**
     * Adjust debugging values
     *
     * @param string $type debugging type
     * @param string $name a unique identifier
     * @param array $arr key value pair that we want to modiff
     * @return null
     */
    public static function modify($type, $name, $arr)
    {
        if (!config::get('hathoora.logger.profiling'))
            return null;
        
        if (!$name && !is_array($arr))
            return null;

        if ($name && isset(self::$arrProfile[$type][$name]) && is_array(self::$arrProfile[$type][$name]))
        {
            foreach($arr as $k => $v)
            {
                self::$arrProfile[$type][$name][$k] = $v;
            }
        }
    }    
    
    /**
     * returns microtime difference
     *
     * @param int $a start microtime(false)
     * @param int $b end microtime(false)
     */
    public static function microtimeDiff($a, $b) 
    {
        list($a_dec, $a_sec) = explode(" ", $a);
        list($b_dec, $b_sec) = explode(" ", $b);
        return $b_sec - $a_sec + $b_dec - $a_dec;
    }    
}