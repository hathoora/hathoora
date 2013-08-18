<?php
namespace hathoora
{
    /**
     * splAutoload wrapper
     */
    class autoload
    {
        public static function register($namespace, $path)
        {
            $loader = new \Composer\Autoload\ClassLoader();
            $loader->add($namespace, $path);

            // activate the autoloader
            $loader->register();
        }
    }
}