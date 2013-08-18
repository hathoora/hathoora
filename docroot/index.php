<?php
require_once('../vendor/autoload.php');

$log = new Monolog\Logger('name');


date_default_timezone_set('America/Los_Angeles');
define('HATHOORA_PROFILE_START_TIME', microtime());
define('HATHOORA_ROOTPATH', realpath(__DIR__ . '/..') .'/');
error_reporting(E_ALL ^ E_STRICT);
use hathoora\kernel;
$env = 'dev';

echo '<pre>';
print_r(get_included_files());
print_r($log);
echo '</pre>';

$kernel = new kernel($env);
$kernel->bootstrapWebPage();