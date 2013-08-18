<?php
namespace hathoora\cache;

use hathoora\configure\config,
    hathoora\logger\profiler,
    hathoora\logger\logger;
 
/*
 * cache class wrapper
 */
class cache
{
    /**
     * for debugging
     */
    protected $poolName;
 
    /**
     * cache handler, the real cache class which implements hathooraCacheInterface
     */
    protected $factory;
    
    /**
     * container \hathoora\container
     */
    private $container;
 
    /**
     * Contructor
     *
     */
    public function __construct()
    { }    
 
    /**
     * Set dic container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Builds factory
     *
     * @param string $pool_name
     */
    public function pool($pool_name)
    {
        static $arrPools;
       
        if (!isset($arrPools[$pool_name]))
        {
            $arrPool = $this->container->getConfig('cache.pools.' . $pool_name);
            if (is_array($arrPool))
            {
                $driver = !empty($arrPool['driver']) ? strtolower($arrPool['driver']) : null;
                $arrServers = !empty($arrPool['servers']) ? $arrPool['servers'] : null;
                if ($driver && is_array($arrServers))
                {
                    $class = '\hathoora\cache\cache' . ucwords($driver);
                    $arrConfig = array(
                        'servers' => $arrServers
                    );
                    
                    $arrPools[$pool_name] = new $class($arrConfig);
                }
            }
        }
 
        if (!empty($arrPools[$pool_name]) && is_object($arrPools[$pool_name]))
        {
            $this->poolName = $pool_name;
            $this->factory =& $arrPools[$pool_name];         
        }
       
        return $this;
    }
   
    /**
     * magic function for accessing cache handler properties
     */
    public function __get($name)
    {
        return $this->factory->$name;
    }
 
    /**
     * magic function for setting cache handler properties
     */
    public function __set($name, $value)
    {
        return $this->factory->$name = $value;
    }
 
    /**
     * magic function for checking isset cache handler properties
     */
    public function __isset($name)
    {
        return isset($this->factory->$name);
    }
 
    /**
     * magic function for unsetting cache handler properties
     */
    public function __unset($name)
    {
        unset($this->factory->$name);
    }
 
    /**
     * magic function for accessing cache handler methods
     */
    public function __call($name, $args)
    {
        static $i;
        $i++;
        
        $profiling = config::get('hathoora.logger.profiling');
        $arrDebug = array(
            'name' => current($args));
            
        if ($profiling)
        {
            $arrDebug['poolName'] = $this->poolName;
            $arrDebug['method'] = $name;
            $arrDebug['start'] = microtime();
        }
       
        $return = null;
        if ($this->factory)
            $return = call_user_func_array(array($this->factory, $name), $args);
        $status = ($return && !is_null($return)) ? 1 : 0;
        
        if ($profiling)
        {
            $arrDebug['status'] = $status;
            $arrDebug['end'] = microtime();
            
            profiler::profile('cache', $i, $arrDebug);
        }        
        // logging
        logger::log(logger::LEVEL_INFO, 'CACHE (pool = '. $this->poolName .') ' . $name .': '. $arrDebug['name'] .', status: '. $status);
       
        return $return;
   }
}