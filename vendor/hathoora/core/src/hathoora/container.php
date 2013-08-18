<?php
namespace hathoora
{
    use hathoora\registry,
        hathoora\observer,
        hathoora\http\request;

    class container
    {
        /**
         * Container constructor
         *
         */
        public function __construct()
        { }

        /**
         * Getting container
         *
         * @return \hathoora\container
         */
        final public static function getContainer()
        {
            return registry::get('hathooraKernel')->container;
        }

        /**
         * Returns instance of \hathoora\kernel
         *
         * @return \hathoora\kernel
         */
        final public static function getKernel()
        {
            return registry::get('hathooraKernel');
        }

        /**
         * Returns instance of \hathoora\observer
         *
         * @return \hathoora\observer
         */
        final public static function getObserver()
        {
            return registry::get('hathooraKernel')->observer;
        }

        /**
         * Returns instance of \hathoora\router\request
         *
         * @return \hathoora\router\request
         */
        final public static function getRouteRequest()
        {
            return registry::get('hathooraKernel')->routeRequest;
        }

        /**
         * Returns instance of \hathoora\controller\base
         *
         * @return \hathoora\controller\base
         */
        final public static function getController()
        {
            return registry::get('hathooraKernel')->controller;
        }

        /**
         * Returns instance of \hathoora\http\response
         *
         * @return \hathoora\http\response
         */
        final public static function getResponse()
        {
            return registry::get('hathooraKernel')->response;
        }

        /**
         * Returns instance of \hathoora\router\dispatcher
         *
         * @return \hathoora\router\dispatcher
         */
        final public static function getRouteDispatcher()
        {
            return registry::get('hathooraKernel')->routeDispatcher;
        }

        /**
         * Get request object
         *
         * @return \hathoora\http\request
         */
        final public static function getRequest()
        {
            return request::make();
        }

        /**
         * function for checking if has config
         *
         * @param string $name
         * @return bool
         */
        final public static function hasConfig($name)
        {
            return registry::hasConfig($name);
        }

        /**
         * function for setting config
         *
         * @param $name
         * @param mixed $value to be stored
         */
        final public static function setConfig($name, $value)
        {
            registry::setConfig($name, $value);
        }

        /**
         * function for getting config
         *
         * @param string $name
         * @return mixed
         */
        final public static function getConfig($name)
        {
            return registry::getConfig($name);
        }

        /**
         * This function returns all configs
         */
        final public static function getAllConfig()
        {
            return registry::getAllConfig();
        }

        /**
         * function for checking if service exists
         *
         * @param string $name
         * @return bool
         */
        final public static function hasService($name)
        {
            return registry::hasService($name);
        }

        /**
         * function for getting service
         *
         * @param string $name
         * @param array $args to pass to service
         * @return mixed
         */
        final public static function getService($name, $args = array())
        {
            $value = registry::getService($name, $args);

            return $value;
        }
    }
}