<?php

class DataService {
    
    /**
     * WebSocker controller to be injected
     * @var Extjs_Controller
     */
    public $controller;
    
    private $gridData = array(
        array('company' => '3m Co', 'price' => '24.55', 'change' => '0.02', 'pctChange' => '0.03', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Alcoa Inc', 'price' => '23.22', 'change' => '0.42', 'pctChange' => '1.47', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Altria Group Inc', 'price' => '11.22', 'change' => '0.2', 'pctChange' => '1.5', 'lastChange' => '9/1 12:00am'),
        array('company' => 'American Express Company', 'price' => '44.33', 'change' => '0.42', 'pctChange' => '1.2', 'lastChange' => '9/1 12:00am'),
        array('company' => 'American International Group, Inc.', 'price' => '32.44', 'change' => '0.3', 'pctChange' => '2.5', 'lastChange' => '9/1 12:00am'),
        array('company' => 'AT&T Inc.', 'price' => '23.21', 'change' => '2.3', 'pctChange' => '4.3', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Boeing Co.', 'price' => '41.21', 'change' => '3.2', 'pctChange' => '2.3', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Caterpillar Inc.', 'price' => '55.44', 'change' => '4.2', 'pctChange' => '2.2', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Citigroup, Inc.', 'price' => '35.21', 'change' => '2.2', 'pctChange' => '4.2', 'lastChange' => '9/1 12:00am'),
        array('company' => 'E.I. du Pont de Nemours and Company', 'price' => '12', 'change' => '2.2', 'pctChange' => '1.1', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Exxon Mobil Corp', 'price' => '32.22', 'change' => '2.4', 'pctChange' => '0.2', 'lastChange' => '9/1 12:00am'),
        array('company' => 'General Electric Company', 'price' => '2.3', 'change' => '4.2', 'pctChange' => '0.12', 'lastChange' => '9/1 12:00am'),
        array('company' => 'General Motors Corporation', 'price' => '12.2', 'change' => '5.1', 'pctChange' => '0.16', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Hewlett-Packard Co.', 'price' => '12.1', 'change' => '2.4', 'pctChange' => '0.18', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Honeywell Intl Inc', 'price' => '2.5', 'change' => '6.7', 'pctChange' => '0.245', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Intel Corporation', 'price' => '54.3', 'change' => '3.1', 'pctChange' => '0.65', 'lastChange' => '9/1 12:00am'),
        array('company' => 'International Business Machines', 'price' => '3.5', 'change' => '5.1', 'pctChange' => '0.42', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Johnson & Johnson', 'price' => '2.6', 'change' => '4.2', 'pctChange' => '0.16', 'lastChange' => '9/1 12:00am'),
        array('company' => 'JP Morgan & Chase & Co', 'price' => '5.2', 'change' => '2.1', 'pctChange' => '0.77', 'lastChange' => '9/1 12:00am'),
        array('company' => 'McDonald\'s Corporation', 'price' => '6.2', 'change' => '2.6', 'pctChange' => '0.54', 'lastChange' => '9/1 12:00am'), 
        array('company' => 'Merck & Co., Inc.', 'price' => '7.1', 'change' => '2.7', 'pctChange' => '0.34', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Microsoft Corporation', 'price' => '8.6', 'change' => '2.8', 'pctChange' => '0.25', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Pfizer Inc', 'price' => '8.6', 'change' => '2.7', 'pctChange' => '0.35', 'lastChange' => '9/1 12:00am'),
        array('company' => 'The Coca-Cola Company', 'price' => '6.3', 'change' => '1.6', 'pctChange' => '0.45', 'lastChange' => '9/1 12:00am'),
        array('company' => 'The Home Depot, Inc.', 'price' => '3.2', 'change' => '0.9', 'pctChange' => '0.34', 'lastChange' => '9/1 12:00am'),
        array('company' => 'The Procter & Gamble Company', 'price' => '6.8', 'change' => '0.8', 'pctChange' => '0.35', 'lastChange' => '9/1 12:00am'),
        array('company' => 'United Technologies Corporation', 'price' => '6.3', 'change' => '0.7', 'pctChange' => '0.45', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Verizon Communications', 'price' => '2.7', 'change' => '0.5', 'pctChange' => '0.46', 'lastChange' => '9/1 12:00am'),
        array('company' => 'Wal-Mart Stores, Inc.', 'price' => '1.9', 'change' => '0.2', 'pctChange' => '0.46', 'lastChange' => '9/1 12:00am'),
    );
    
    /**
     * Testing public method
     * 
     * @remotable
     * @return void
     */
    public function testMethod($arg) {
        
        
        $resp = array();
        $resp['type'] = 'event';
        $resp['name'] = 'message';
        $resp['data'] = "Hello nasty";
        
        if ($this->controller != null) {
            $this->controller->sendEvent($resp);
        }
        return $resp;
    }
    
    /**
     * Must not be visible
     * 
     * @remotable
     * @return void
     */
    private function testPrivateMethod() {
        $event = array('type' => "event", "name" => "message", "data" => "hello hello");
        
        return $event;
    }
    
    /**
     * 
     * @remotable
     * 
     * @return array
     */
    public function readWS() {
        return array(
                array("name" => "Item List",
                      "url" => "http://192.168.16.26:7047/DynamicsNAV/WS/CRONUS International Ltd./Page/Itel_List"),
                array("name" => "Company Info",
                      "url" => "http://192.168.16.26:7047/DynamicsNAV/WS/CRONUS International Ltd./Page/Company_Info"),
                array("name" => "Order List",
                      "url" => "http://192.168.16.26:7047/DynamicsNAV/WS/CRONUS International Ltd./Page/Order List")    
            );
    }
    
    /**
     * @remotable
     * @formHandler
     * 
     * @param $post
     * @param $files
     * 
     */
    public function submitWSAction($post, $files) {
        //print_r($post);
        //while(true) {
        $resp = array();
        $resp['type'] = 'event';
        $resp['name'] = 'message';
        $resp['data'] = $post;
        
        $this->sendUpdates($resp);   
        $this->controller->sendEvent($resp);
        $this->controller->sendEvent($resp);
        
        $resp = array();
        $resp['success'] = true;
        $resp['data'] =$post;
        //sleep(10);
        
        return $resp;
        //}
    }
    
    public function sendUpdates($resp) {
        $task = new SleepingTask();
        $task->controller = $this->controller;
        $task->fork($resp);
        
    }
    
    /**
     * @remotable
     * 
     * @return array
     */
    public function getGridData($params) {
        
        $start = $params->start;
        $limit = $params->limit;
        
        print_r($params);
        //array_push($this->gridData, array('totalCount'=>count($this->gridData)));
        $resp = array();
        $resp['totalCount'] = count($this->gridData);
        $resp['success'] = true;
        $resp['data'] = array_slice ($this->gridData, $start, $limit); 
        return $resp;
    }
    
    /**
     * 
     * @event
     */
    public function updateEvent() {
        
        //while(true) {
            sleep(10);
            $resp = array();
            $resp['event'] = true;
            $resp['name'] = "message";
            $resp['data'] = "hello ".date(time());
        return $resp;
        //}
    }
    
    /**
     * @remotable
     * 
     * @param $params
     * 
     * @return array
     */
    public function loadWSAction($params) {
        //print_r($params);
        $resp = array();
        $resp['success'] = true;
        $resp['data'] = array("name"=> 'Name', "url"=>"URL");
        return $resp;
    }
}

class Task {

    protected $pid;
    protected $ppid;
    public $controller;

    function __construct(){
    }

    function fork($resp){
        $pid = pcntl_fork();
        if ($pid == -1)
            throw new Exception ('fork error on Task object');
        elseif ($pid) {
            # we are in parent class
            $this->pid = $pid;
            # echo "< in parent with pid {$his->pid}\n";
        } else{
            # we are is child
            $this->run($resp);
        }
    }

    function run($resp = null){
        # echo "> in child {$this->pid}\n";
        # sleep(rand(1,3));
        $this->ppid = posix_getppid();
        $this->pid = posix_getpid();
    }

    # call when a task in finished (in parent)
    function finish(){
        echo "task finished {$this->pid}\n";
    }

    function pid(){
        return $this->pid;
    }
}

class SleepingTask extends Task{
    function run($resp){
        parent::run();
        echo "> in child {$this->pid}\n";
            $i = 0;
            while (true) {
                if ($this->controller != null) {
                    $this->controller->sendEvent($resp);
                } else {
                    print_r("Controller is not defined");
                }
                sleep(2);
                $i++;
                if ($i == 5) {
                    break;
                }
            }
        
        echo "> child done {$this->pid}\n";
        exit(0);
    }
}