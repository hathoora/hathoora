<?php
require_once('../vendor/autoload.php');

date_default_timezone_set('America/Los_Angeles');
define('HATHOORA_PROFILE_START_TIME', microtime());
define('HATHOORA_ROOTPATH', realpath(__DIR__ . '/..') .'/');
error_reporting(E_ALL ^ E_STRICT);
use hathoora\kernel;
$env = 'dev';

$kernel = new kernel($env);
$kernel->bootstrapWebPage();
