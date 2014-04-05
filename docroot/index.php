<?php
define('HATHOORA_ROOTPATH', realpath(__DIR__ . '/..') .'/');
require_once('../vendor/autoload.php');

// use prod environment by default
$env = 'prod';

if (isset($_SERVER['HATHOORA_ENV']))
    $env = $_SERVER['HATHOORA_ENV'];

use hathoora\kernel;


$kernel = new kernel($env);
$kernel->bootstrapWebPage();