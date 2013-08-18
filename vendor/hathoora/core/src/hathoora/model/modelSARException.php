<?php
namespace hathoora\model;

/**
 * Custom exception
 */
class modelSARException extends \Exception
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