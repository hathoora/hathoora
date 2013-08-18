<?php
namespace hathoora\logger;

use hathoora\configure\config;

class logger
{
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_FATAL = 'FATAL';
    
    static $arrLogLevels = array(
        'DEBUG' => 1,
        'INFO' => 3,
        'WARNING' => 5,
        'ERROR' => 7,
        'FATAL' => 9
    );

    /**
     * static variable for storing information
     */
    public static $arrLog;

    /**
     * logging
     *
     * @param string $level
     * @param string $message
     * @return null
     */
    public static function log($level, $message)
    {
        if (!config::get('hathoora.logger.logging.enabled'))
            return null;
        
        $minLevel = config::get('hathoora.logger.logging.level');
        if (!$minLevel)
            $minLevel = 'INFO';
        
        $minLevel = 'DEBUG';
            
        if (isset(self::$arrLogLevels[$minLevel]))
        {
            $minLogLevel = self::$arrLogLevels[$minLevel];
            // check for minimum log level
            if (self::$arrLogLevels[$level] >= $minLogLevel)
            {
                self::$arrLog[] = array(
                                        'time' => microtime(),
                                        'memory' => memory_get_usage(),
                                        'level' => $level,
                                        'message' => $message);
            }
        }
    }
}