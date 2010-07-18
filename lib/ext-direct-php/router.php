<?php
session_start();

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
    
require_once dirname(__FILE__).'/../../application/SetIncludePath.php';

require_once('ext-direct-php/ExtDirect/API.php');
require_once('ext-direct-php/ExtDirect/Router.php');

// this should alwasy be set but if its not, then execute api.php without outputting it
if(!isset($_SESSION['ext-direct-state'])) {
    ob_start();
    include('api.php');
    ob_end_clean();
}

$api = new ExtDirect_API();
$api->setState($_SESSION['ext-direct-state']);
  
$router = new ExtDirect_Router($api);
$router->dispatch();
$router->getResponse(true); // true to print the response instantly