<?php
namespace hathoora\configure;

use hathoora\registry,
    hathoora\logger\logger;

/**
 * This class does all the heavy lifting when a service is called
 */
class serviceManager
{
    // storage for static services
    static $arrServices;
    
    // for caching objects
    static $cachedObjects;

    /**
     * Get the service
     *
     * @param string $key for which it is known for
     * @param array $arrService defined in services.ini
     * @param array $methodParams to pass to service
     * @throws serviceNotReachable
     * @return mixed|null
     */
    public static function get($key, $arrService, $methodParams = array())
    {
        $error = false;
        $service = $classCacheHash = $reachable = null;

        if (!isset(self::$arrServices[$key]))
        {
            if (is_array($arrService))
            {
                // class to instantiate
                $class = isset($arrService['class']) ? $arrService['class'] : null;
                // args to pass to $class
                $args = isset($arrService['args']) ? $arrService['args'] : array();
                // method to call when the service is called
                $method = isset($arrService['method']) ? $arrService['method'] : null;
                // params to pass to the $method
                $params = isset($arrService['params']) ? $arrService['params'] : $methodParams;
                // calls are the methods called automatically when service is loaded
                $calls = isset($arrService['calls']) ? $arrService['calls'] : null;
                // when a service is using a factory
                $factory_service = isset($arrService['factory_service']) ? $arrService['factory_service'] : null;
                // method to call from factory method when the service is called
                $factory_method = isset($arrService['factory_method']) ? $arrService['factory_method'] : null;
                // params to pass to $factory_service
                $factory_method_args = isset($arrService['factory_method_args']) ? $arrService['factory_method_args'] : null;
                // static services that are cached
                $service_type = isset($arrService['type']) ? $arrService['type'] : null;

                $classCached = null;                
                // service is not factory
                if (!$factory_service)
                {
                    $classCacheHash = $key . '::'. $class .'__' . md5(json_encode($args) . $method . md5(json_encode($params)));

                    if (isset(self::$cachedObjects[$classCacheHash]))
                    {
                        $service = self::$cachedObjects[$classCacheHash];
                        $reachable = true; // skip isServiceReachable()
                        $classCached = true;
                    }
                    else
                        $reachable = self::isServiceReachable($key, $class, $args, $method, $params);
                }
                else if ($factory_service)
                {
                    $classCacheHash = $key . '::'. $factory_service .'__' . $factory_method . md5(json_encode($factory_method_args));
                    
                    if (isset(self::$cachedObjects[$classCacheHash]))
                    {
                        $service = self::$cachedObjects[$classCacheHash];
                        $reachable = true; // skip isServiceReachable()
                    }
                    else
                        $reachable = true;
                }
                    
                if ($reachable === true)
                {
                    if (!$factory_service)
                    {
                        // @todo pass $args (and also pass via ref)
                        $service = new $class();
                    
                        // any calls to make?
                        if (!$classCached && is_array($calls) && count($calls))
                        {
                            foreach ($calls as $call => $arrCallParams)
                            {
                                // special traetmeant for call params
                                if (is_array($arrCallParams))
                                {
                                    foreach ($arrCallParams as $i => $callParam)
                                    {
                                        $arrCallParams[$i] = self::getParamsValue($callParam);
                                    }
                                }
                                
                                // @todo error check
                                call_user_func_array(array(
                                                            $service,
                                                            $call), 
                                                            $arrCallParams);                        
                            }
                        }
                        
                        self::$cachedObjects[$classCacheHash] = $service; // store to internal caching
                        
                        if ($method)
                        {
                            $service = call_user_func_array(array(
                                                            $service,
                                                            $method), 
                                                            $params);
                        }
                    }
                    else if ($factory_service)
                    {
                        $service = registry::getService(substr($factory_service, 1, -1));
                        self::$cachedObjects[$classCacheHash] = $service; // store to internal caching

                        // factory method params
                        if ($factory_method)
                        {
                            if (is_array($factory_method_args) && count($factory_method_args))
                            {
                                foreach ($factory_method_args as $i => $arg)
                                    $factory_method_args[$i] = self::getParamsValue($arg);
                            }
                            
                            $service = call_user_func_array(array(
                                                                $service,
                                                                $factory_method), 
                                                                $factory_method_args);
                        }
                    }
                    
                    if ($service_type == 'static')
                       self::$arrServices[$key] = $service;
                }
                // set the error
                else
                    $error = $reachable;
            }
        }
        else
            $service = self::$arrServices[$key];

        
        // throw exception for services not found..
        if ($error)
            throw new serviceNotReachable($error);
        
        return $service;
    }
    
    /**
     * Checks to see if a service exists (instantiable, callable etc..)
     *
     * @param string $key for which service is known for
     * @param string $class name (with fully qualified namespace)
     * @param array $args for constructor
     * @param string $method name
     * @param string $params for method name
     *
     * @return mixed|bool true (=== bool) when all good, else error string
     */
    private static function isServiceReachable($key, $class, $args, $method, $params)
    {
        $error = false;
        $errorMsg = null;
        
        if ($class)
        {
            $instantiable = false;
            try {
                $reflectionClass = new \ReflectionClass($class);
                $instantiable = $reflectionClass->IsInstantiable();
            }
            catch (\ReflectionException $e)
            { 
                $errorMsg = $e->getMessage();
            }
            
            if ($instantiable)
            {
                if ($method)
                {
                    $callable = is_callable(array(
                                                $class, 
                                                $method));                     
                    if (!$callable)
                        $error = 'Service method for '. $key .' ('. $method .'()) is not callable.';
                }
            }
            else
                $error = 'Service class for '. $key .' ('. $class .') is not instantiable. Please make you have entered the class name correctly. '. $errorMsg;
        }
        else
            $error = 'Service class for '. $key .' is not reachable. Please make you have entered the class name correctly. '. $errorMsg;
            
        if (!$error)
            return true;
            
        return $error;
    }
    
    /**
     * A function that returns appropriate value of params
     * a service is @service@
     * a config param is %configname%
     * else it is a string as is
     */
    private static function getParamsValue($str)
    {
        // it is a service which has a format of @service@
        if (substr($str, 0, 1) == '@')
            $val =& registry::getService(substr($str, 1, -1));
        // its a config which has format of %config.name%
        else if (substr($str, 0, 1) == '%')
            $val =& registry::getConfig(substr($str, 1, -1));
        else
            $val =& $str;
            
        return $val;
    }
    
    /**
     * There are certain services that are added only when configuration is enabled
     * ex translation service is enabled only when translation.enabled = 1
     */
    public static function loadDefaultServicesFromConfig()
    {
        // container
        registry::setConfig('services.container', array(
                                                        'class' => '\hathoora\container',
                                                        'method' => 'getContainer'));

        // translation service?
        if (registry::getConfig('hathoora.translation.enabled'))
        {
            logger::log(logger::LEVEL_DEBUG, 'Service "translator" has been added because of <i>hathoora.translation.enabled</i>.');
            registry::setConfig('services.translator', array(
                                                                'class' => '\hathoora\translation\translator',
                                                                'method' => 't',
                                                                'calls' => array(
                                                                                    'setContainer' => array('@container@'))));
        }
        
        // translation service?
        if (registry::getConfig('hathoora.gulaboo.assets.enabled'))
        {
            logger::log(logger::LEVEL_DEBUG, 'Service "assets" has been added because of <i>hathoora.gulaboo.assets.enabled</i>.');
            registry::setConfig('services.gulabooAssets', array(
                                                                'class' => '\hathoora\gulaboo\assets',
                                                                'calls' => array(
                                                                                    //'setContainer' => array('@container@')
                                                                                )));
        }        
    }
}