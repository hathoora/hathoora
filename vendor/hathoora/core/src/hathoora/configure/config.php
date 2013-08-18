<?php
namespace hathoora\configure
{

    use hathoora\registry,
        hathoora\logger\logger;

    /**
     * Configuration load class
     */
    class config implements configInterface
    {
        static $arrConfigFiles;

        /**
         * The function loads configuration files
         *
         * Any configuration defined in the later ones will overwrite previously defined ones.
         *
         * @param mixed $arrFiles Array of file paths or a string of single file path
         */
        public function __construct($arrFiles = array())
        {
            $arrConfig = registry::get('hathooraConfig');

            if (!is_array($arrFiles))
                $arrFiles = (array) $arrFiles;

            // load config files
            if (is_array($arrFiles))
            {
                foreach ($arrFiles as $file)
                {
                    if (file_exists($file))
                    {
                        self::$arrConfigFiles[] = basename($file);
                        $ymlConfig = Spyc::YAMLLoad($file);
                        if (is_array($ymlConfig))
                        {
                            if (isset($ymlConfig['imports']) && is_array($ymlConfig['imports']))
                            {
                                foreach ($ymlConfig['imports'] as $arrImport)
                                {
                                    if (isset($arrImport['resource']))
                                    {
                                        $importFile = $arrImport['resource'];
                                        $importFile = dirname($file) . '/'. $importFile;

                                        new config(array($importFile));
                                        $arrConfig = registry::get('hathooraConfig');
                                    }
                                }

                                unset($ymlConfig['imports']);
                            }

                            foreach($ymlConfig as $k => $v)
                            {
                                if (is_array($v))
                                {
                                    foreach($v as $k2 => $v2)
                                    {
                                        $arrConfig[$k][$k2] = $v2;
                                    }
                                }
                                else
                                    $arrConfig[$k] = $v;
                            }
                        }
                    }
                    else
                    {
                        logger::log(logger::LEVEL_INFO, 'Configuration file ('. $file .') does not exist.');
                    }
                }
            }

            // store config in registry
            registry::set('hathooraConfig', $arrConfig);

            logger::log(logger::LEVEL_DEBUG, 'Configuration loaded: <br/> <pre>' . print_r(self::$arrConfigFiles, true) .'</pre>');
        }

        /**
         * Sets a variable and its value.
         *
         * This function will overwrite any existing variable name
         *
         * @param string $key variable name
         * @param mixed $value to be stored
         */
        public static function set($key, $value)
        {
            registry::setConfig($key, $value);
        }

        /**
         * gets the value of a variable
         *
         * @param string $key variable name
         * @return mixed false when not found
         */
        public static function get($key)
        {
            return registry::getConfig($key);
        }

        /**
         * has config
         *
         * @param string $key variable name
         * @return bool
         */
        public static function has($key)
        {
            return registry::hasConfig($key);
        }

        /**
         * Get all configs
         */
        public static function getAll()
        {
            return registry::getAllConfig();
        }
    }
}