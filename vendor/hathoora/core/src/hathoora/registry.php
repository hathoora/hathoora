<?php
namespace hathoora
{

    use hathoora\configure\serviceManager;

    /**
     * registry class
     */
    class registry
    {
        /**
         * storage
         */
        public static $storage = array(
                                        'hathooraConfig' => array(), //  hathooraConfig stores config.inis
                                        'hathooraKernel' => null, // hathooraKernel stores hathoora kernel
                                        );

        /**
         * sets a variable and its value
         *
         * @param string $key variable name
         * @param mixed $value to be stored
         */
        public static function set($key, $value)
        {
            self::setTypeValue($key, $value);
        }

        /**
         * sets a variable and its value
         *
         * @param string $key variable name
         * @param mixed $value to be stored
         */
        public static function setByRef($key, &$value)
        {
            self::setTypeValueByRef($key, $value);
        }

        /**
         * gets the value of a variable
         *
         * @param string $key variable name
         * @return mixed, false when not found
         */
        public static function get($key)
        {
            return self::getTypeValue($key);
        }

        /**
         * set hathoora config
         *
         * @param string $key variable name
         * @param mixed $value to be stored
         */
        public static function setConfig($key, $value)
        {
            self::setTypeValue($key, $value, 'hathooraConfig');
        }

        /**
         * check if hathoora config exists
         *
         * @param string $key variable name
         * @return bool
         */
        public static function hasConfig($key)
        {
            return self::getTypeValue($key, 'hathooraConfig');
        }

        /**
         * get hathoora config
         *
         * @param string $key variable name
         * @return mixed the value or else false
         */
        public static function getConfig($key)
        {
            $val = self::getTypeValue($key, 'hathooraConfig');
            /*
            $possibileJson = @json_decode($val, true);
            if (is_array($possibileJson) && count($possibileJson))
                $val = $possibileJson;
            */

            return $val;
        }

        /**
         * Get all configs
         */
        public static function getAllConfig()
        {
            return self::$storage['hathooraConfig'];
        }

        /**
         * check if there is hathoora service
         *
         * @param string $key variable name
         * @param array $args (for future)
         * @return bool
         */
        public static function hasService($key, $args = array())
        {
            $arrService = self::getConfig('services');

            return isset($arrService[$key]);
        }

        /**
         * get hathoora service
         *
         * @param string $key variable name
         * @param array $args to pass to service
         * @return mixed the value or else false
         */
        public static function getService($key, $args = array())
        {
            $arrService = self::getConfig('services');

            if (self::hasService($key))
                $arrService =& $arrService[$key];
            else
                $arrService = array();

            return serviceManager::get($key, $arrService, $args);
        }

        /**
         * Get all services
         */
        public static function getAllServices()
        {
            return self::getConfig('services');
        }

        /**
         * Internal function for setting type's key which takes care of "." segmentation
         * e.g get(domain) would set (and overwrite) all things underneath it
         * get(domain.site) would set specific key value
         *
         * @param string $key variable name
         * @param string $value to store
         * @param bool|string $type (optional) of storage ex hathooraConfig etc..
         * @return void
         */
        private static function setTypeValue($key, $value, $type = false)
        {
            // set in specific type
            if ($type)
            {
                if (preg_match('/^(.+?)\.(.+?)$/', $key, $arrMatch))
                {
                    $arrParts = explode('.', $key);
                    if (is_array($arrParts))
                    {
                        $count = count($arrParts);
                        $i = 0;
                        $lastSection =& self::$storage[$type];
                        foreach($arrParts as $section)
                        {
                            $i++;
                            if (isset($lastSection[$section]))
                            {
                                if ($count == $i)
                                    $lastSection[$section] = $value;
                                else
                                    $lastSection =& $lastSection[$section];
                            }
                            else
                            {
                                if ($count == $i)
                                    $lastSection[$section] = $value;
                                else
                                {
                                    $lastSection[$section] = array();
                                    $lastSection =& $lastSection[$section];
                                }
                            }
                        }
                    }
                }
                // when key has section.key format
                else if (preg_match('/^(.+?)\.(.+?)$/', $key, $arrMatch))
                {
                    $sectionKey = array_pop($arrMatch);
                    $section = array_pop($arrMatch);

                    self::$storage[$type][$section][$sectionKey] = $value;

                }
                else
                    self::$storage[$type][$key] = $value;
            }
            // set at root level
            // @todo prevent overwriting specific types
            else
                self::$storage[$key] = $value;
        }

        /**
         * Internal function for setting type's key which takes care of "." segmentation
         * e.g get(domain) would set (and overwrite) all things underneath it
         * get(domain.site) would set specific key value
         *
         * @param string $key variable name
         * @param string $value to store
         * @param string $type (optional) of storage ex hathooraConfig etc..
         * @return null
         */
        private static function setTypeValueByRef($key, &$value, $type = null)
        {
            // set in specific type
            if ($type)
            {
                // @todo fix https://github.com/attozk/hathoora/issues/4
                // when key has section.key format
                if (preg_match('/^(.+?)\.(.+?)$/', $key, $arrMatch))
                {
                    $sectionKey = array_pop($arrMatch);
                    $section = array_pop($arrMatch);

                    self::$storage[$type][$section][$sectionKey] =& $value;
                }
                else
                    self::$storage[$type][$key] =& $value;
            }
            // set at root level
            // @todo prevent overwriting specific types
            else
                self::$storage[$key] =& $value;
        }

        /**
         * Internal function for getting type's key which takes care of "." segmentation
         * e.g get(domain) would return an array of all configs underneath it, whereras
         * get(domain.site) would return specific key value
         *
         * @param string $key variable name
         * @param string $type (optional) of storage ex hathooraConfig etc..
         * @return mixed the value or else false
         */
        private static function getTypeValue($key, $type = null)
        {
            $value = null;
            $found = false;

            // lookup in specific type
            if ($type)
            {
                // when key has section.key format
                if (preg_match('/^(.+?)\.(.+?)$/', $key, $arrMatch))
                {
                    //@todo: i think this can be further optimized
                    $arrParts = explode('.', $key);
                    $arrSecLoop = self::$storage[$type];
                    foreach($arrParts as $section)
                    {
                        if (!isset($arrSecLoop[$section]) || !is_array($arrSecLoop))
                        {
                            $found = false;
                            $value = null;
                            break;
                        }

                        if (isset($arrSecLoop[$section]) && is_array($arrSecLoop))
                        {
                            $value = $arrSecLoop[$section];
                            $arrSecLoop = $arrSecLoop[$section];
                            $found = true;
                        }
                    }
                }

                if (!$found && isset(self::$storage[$type][$key]))
                    $value = self::$storage[$type][$key];
            }
            // lookup at root level
            else if (isset(self::$storage[$key]))
                $value = self::$storage[$key];

            return $value;
        }
    }
}