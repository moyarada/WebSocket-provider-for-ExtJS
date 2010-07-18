<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', 
    realpath(dirname(__FILE__) . '/../../application'));
    
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    realpath(APPLICATION_PATH . '/../../../lib'),
    get_include_path(),
)));

session_start();

// Include ExtDirect PHP Helpers
require_once('ext-direct-php/ExtDirect/API.php');
require_once('ext-direct-php/ExtDirect/CacheProvider.php');

//$cache = new ExtDirect_CacheProvider('ext-direct-php/cache/api_cache.txt');
$api = new ExtDirect_API();

// Specify WebSocket endpoint here
$api->setRouterUrl('ws://localhost:7777/grid/updates'); // default
$api->setTransportType('socket');
//$api->setCacheProvider($cache);
$api->setNamespace('MR.direct');
$api->setDescriptor('MR.direct.REMOTING_API');
$api->setDefaults(array(
    'autoInclude' => true,
    'basePath' => dirname(__FILE__).'/../../application/services'
));

$api->add(
    array(
        'DataService'
    )
);

$api->output();

$_SESSION['ext-direct-state'] = $api->getState();