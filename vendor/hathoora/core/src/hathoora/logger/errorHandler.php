<?php
namespace hathoora\logger;

class errorHandler
{
    /**
     * Custom error handler set via set_error_handler()
     *
     * @param $errno, contains the level of the error raised, as an integer.
     * @param $errstr, contains the error message, as a string.
     * @param $errfile, which contains the filename that the error was raised in, as a string.
     * @param $errline, which contains the line number the error was raised at, as an integer.
     * @param $errcontext, which is an array that points to the active symbol table at the point the error occurred.
     *      In other words, errcontext will contain an array of every variable that existed in the scope the error 
     *      was triggered in. User error handler must not modify error context.
     */
    public function customErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        // This error code is not included in error_reporting
        if (!(error_reporting() & $errno)) 
        {
            return;
        }
        
        // taken from @http://www.php.net/manual/en/function.set-error-handler.php#74881
        switch($errno)
        {
             case E_ERROR:               echo "Error";                  break;
             case E_WARNING:             echo "Warning";                break;
             case E_PARSE:               echo "Parse Error";            break;
             case E_NOTICE:              echo "Notice";                 break;
             case E_CORE_ERROR:          echo "Core Error";             break;
             case E_CORE_WARNING:        echo "Core Warning";           break;
             case E_COMPILE_ERROR:       echo "Compile Error";          break;
             case E_COMPILE_WARNING:     echo "Compile Warning";        break;
             case E_USER_ERROR:          echo "User Error";             break;
             case E_USER_WARNING:        echo "User Warning";           break;
             case E_USER_NOTICE:         echo "User Notice";            break;
             case E_STRICT:              echo "Strict Notice";          break;
             case E_RECOVERABLE_ERROR:   echo "Recoverable Error";      break;
             default:                    echo "Unknown error ($errno)"; break;
        }
        echo ":</b> <i>$errstr</i> in <b>$errfile</b> on line <b>$errline</b>\n<br/>";
        
        if(function_exists('ssdebug_backtrace'))
        {
            echo "Backtrace:\n<br/>";
            $arrBacktrace = debug_backtrace();
            array_shift($arrBacktrace);
            
            foreach($arrBacktrace as $i => $arrBt)
            {
                #{$arrBt['class']}{$arrBt['type']}{$arrBt['function']}
                echo "[$i] in function <b></b>";
                if($arrBt['file']) echo " in <b>{$arrBt['file']}</b>";
                if($arrBt['line']) echo " on line <b>{$arrBt['line']}</b>";
                echo "<br/>";
            }
            echo "<br/>";
        }        
         
         
        /* Don't execute PHP internal error handler */
        return;
    }
        
    /**
     * Custom exception handler set via set_exception_handler()
     */
    function customExceptionHandler($e) 
    {
        exit('Huston! We have a problem: '.$e);
    }
}
