<?php
// class for auto loading
require_once(__DIR__ . '/../vendor/hathoora/autoloader.php');

// define name spaces here
$arrNameSpaces = array(
    'hathoora' => __DIR__ .'/../vendor',
    'Smarty' => __DIR__ .'/../vendor',
    'app' => __DIR__ .'/../',
);

// declare name spaces
foreach ($arrNameSpaces as $ns => $path)
{
    spl_autoload_register(array(new autoloader($ns, $path), 'loadClass'));
}

/**
 * printr
 */
function printr($arr, $die = false)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    
    if ($die)
        die();
}


function vardump($arr, $die = false)
{
    echo '<pre>';
    var_dump($arr);
    echo '</pre>';
    
    if ($die)
        die();
}