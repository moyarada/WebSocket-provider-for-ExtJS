<?php
defined('APPLICATION_PATH')
            || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../../examples/grid/application'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    realpath(APPLICATION_PATH . '/../../../lib'),
    get_include_path(),
)));

require_once('ext-direct-php/ExtDirect/API.php');
require_once('ext-direct-php/ExtDirect/Router.php');

class Grid_Controller extends Controller {
    /**
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct(HTTPRequest $request, Dispatcher $dispatcher)
    {
        parent::__construct($request, $dispatcher);
    }
    
    public function process_event(Controller $ctrl = null)
    {
        
    }
    
    public function updates()
    {
        $request = $this->request->get_request_var('data');
        
        if ($request) {
            
            if(!isset($_SESSION['ext-direct-state'])) {
                ob_start();
                require_once('public/api/api.php');
                ob_end_clean();
            }
            
            $api = new ExtDirect_API();
            $api->setState($_SESSION['ext-direct-state']);
              
            $router = new ExtDirect_Router($api, $request, $this);
            
            $router->dispatch();
            $resp = $router->getResponse(false); // true to print the response instantly
            
            
            
            $response = new HTTPResponse(200);
            
            $response->set_body($resp, false);
            $response->set_default_headers();
   
            $this->write($response);
        }
    }
    
    public function sendEvent($event) {
        $listeners = $this->dispatcher->get_listeners();
        
        $response = new HTTPResponse(200);
        
        $response->set_body($event, true);
        
        $this->write($response);
    }
    
}