<?php
namespace hathoora\configure
{

    /**
     * configuration interface
     */
    interface configInterface
    {
        /**
         * The function loads configuration files
         *
         * Any configuration defined in the later ones will overwrite previously defined ones.
         *
         * @param mixed $arrLocation Array of file paths or a string of single file path or database params to fetch from db
         */
        public function __construct($arrLocation = array());

        /**
         * Sets a variable and its value.
         *
         * This function will overwrite any existing variable name
         *
         * @param string $key variable name
         * @param mixed $value to be stored
         */
        public static function set($key, $value);

        /**
         * gets the value of a variable
         *
         * @param string $key variable name
         * @return mixed false when not found
         */
        public static function get($key);

        /**
         * Get all configs
         */
        public static function getAll();
    }
}