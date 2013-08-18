<?php
namespace hathoora\configure;

/**
 * Thrown when a service DNE or not callable
 */
class serviceNotReachable extends \Exception
{
    /**
     *
     * @param string $error 
     */
    public function __construct($error) 
    {
        parent::__construct($error);
    }
}