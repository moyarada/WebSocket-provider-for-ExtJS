<?php
session_start();
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
    
require_once dirname(__FILE__).'/../../application/SetIncludePath.php';

// Include ExtDirect PHP Helpers
require_once('ext-direct-php/ExtDirect/API.php');
require_once('ext-direct-php/ExtDirect/CacheProvider.php');

//$cache = new ExtDirect_CacheProvider('ext-direct-php/cache/api_cache.txt');
$api = new ExtDirect_API();

// Specify WebSocket endpoint here
$api->setRouterUrl('ws://localhost:7777/extjs/updates'); // default
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


/* example of what you can do
// Include ExtDirect PHP Helpers
require_once('ExtDirect/API.php');
require_once('ExtDirect/CacheProvider.php');

// Including one Class myself just for testing purposes
require_once('classes/UserAction.php');


$cache = new ExtDirect_CacheProvider('cache/api_cache.txt');
$api = new ExtDirect_API();

$api->setRouterUrl('router.php'); // default
$api->setCacheProvider($cache);
$api->setNamespace('Ext.ss');
$api->setRemoteAttribute('@remotable'); // default
$api->setFormAttribute('@formHandler'); // default
$api->setDefaults(array(
    'autoInclude' => true,  // if you want to use this you have to make sure that your classes (without the prefix)
                            // are named consistent with the filename and that only one class exists in each file.
    'basePath' => 'classes'
));

$api->add(
    array( // an array with all the classnames.
        'LoginAction' => array('prefix' => 'Test_'), // you can set settings for individual classes 
        'SubscriptionAction', 
        'TeamAction' => array('subPath' => 'Subfolder'), // you can specify classes in a subfolder
        'TemplateAction', 
        'TicketAction', 
        'UserAction' => array('autoInclude' => false)
    ), array( // settings for this batch of classes
        'prefix' => '' // you could use this if your classes have a prefix, defaults to empty string
    )
);

$api->output();
*/