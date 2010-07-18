<?php
class ExtDirect_API {
    private $_routerUrl = 'router.php';
    private $_cacheProvider = null;
    private $_defaults = array();
    private $_classes = array();
    private $_remoteAttribute = '@remotable';
    private $_formAttribute = '@formHandler';
    private $_nameAttribute = '@remoteName';
    private $_namespace = false;
    private $_type = 'remoting';
    private $_parsedClasses = array();
    private $_parsedAPI = array();
    private $_descriptor = 'Ext.app.REMOTING_API';
    private $_eventAttribute = "@event";
    
    public function __construct() {
    }
    
    public function getState() {
        return array(
            'routerUrl' => $this->getRouterUrl(),
            'defaults' => $this->getDefaults(),
            'classes' => $this->getClasses(),
            'remoteAttribute' => $this->getRemoteAttribute(),
            'formAttribute' => $this->getFormAttribute(),
            'nameAttribute' => $this->getNameAttribute(),
            'namespace' => $this->getNamespace(),
            'parsedAPI' => $this->_parsedAPI,
            'descriptor' => $this->_descriptor
        );
    }
    
    public function setState($state) {
        if(isset($state['routerUrl'])) {
            $this->setRouterUrl($state['routerUrl']);
        }
        
        if(isset($state['defaults'])) {
            $this->setDefaults($state['defaults']);
        }
        
        if(isset($state['classes'])) {
            $this->_classes = $state['classes'];
        }
        
        if(isset($state['remoteAttribute'])) {
            $this->setRemoteAttribute($state['remoteAttribute']);
        }
        
        if(isset($state['formAttribute'])) {
            $this->setFormAttribute($state['formAttribute']);
        }

        if(isset($state['nameAttribute'])) {
            $this->setFormAttribute($state['nameAttribute']);
        }
                
        if(isset($state['namespace'])) {
            $this->setNameSpace($state['namespace']);
        }

        if(isset($state['descriptor'])) {
            $this->setDescriptor($state['descriptor']);
        }
        
        if(isset($state['parsedAPI'])) {
            $this->_parsedAPI = $state['parsedAPI'];
        }      
    }
    
    public function add($classes = array(), $settings = array()) {
        $settings = array_merge(
            array(
                'autoInclude' => false,
                'basePath' => '',
                'seperator' => '_',
                'prefix' => '',
                'subPath' => ''
            ), 
            $this->_defaults,
            $settings
        );
        
        if(is_string($classes)) {
            $classes = array($classes);
        }

        foreach($classes as $name => $cSettings) {
            if(is_int($name)) {
                $name = $cSettings;
                $cSettings = array();
            }
            
            $cSettings = array_merge($settings, $cSettings);
            $cSettings['fullPath'] = $this->getClassPath($name, $cSettings);
            
            $this->_classes[$name] = $cSettings;
        }
    }
    
    public function output($print = true) {
        $saveInCache = false;
        if(isset($this->_cacheProvider)) {
            if(!$this->_cacheProvider->isModified($this)) {
                $api = $this->_cacheProvider->getAPI();
                if($print === true) $this->_print($api);
                $this->_parsedClasses = $this->_classes;
                $this->_parsedAPI = $api;  
                return $api;
            }
            $saveInCache = true;
        }           
        
        $api = $this->getAPI();
        
        if($saveInCache) {
            $this->_cacheProvider->save($this);
        }
        
        if($print === true) $this->_print($api);
        return $api;
    }
    
    public function isEqual($old, $new) {
        return serialize($old) === serialize($new);
    }
    
    public function getAPI() {
        if($this->isEqual($this->_classes, $this->_parsedClasses)) {
            return $this->getParsedAPI();
        }
        
        $classes = array();
        $events = array();
        foreach($this->_classes as $class => $settings) {
            $methods = array();
            $eventMethods = array(); // new
            
            if($settings['autoInclude'] === true) {
                $path = !$settings['fullPath']
                    ? $this->getClassPath($class, $settings)
                    : $settings['fullPath'];
                    
                if(file_exists($path)) {
                    require_once($path);
                }
            }

            // here the reflection magic begins
            if(class_exists($settings['prefix'] . $class)) { 
                $rClass = new ReflectionClass($settings['prefix'] . $class);
                $rMethods = $rClass->getMethods();
                foreach($rMethods as $rMethod) {
                    if(
                        $rMethod->isPublic() &&
                        strlen($rMethod->getDocComment()) > 0
                    ) {
                        $doc = $rMethod->getDocComment();
                        $isRemote = !!preg_match('/' . $this->_remoteAttribute . '/', $doc);                       
                        if($isRemote) {
                            $method = array(
                                'name' => $rMethod->getName(),
                                'len' => $rMethod->getNumberOfParameters(),
                            );
                            if(!!preg_match('/' . $this->_nameAttribute . ' ([\w]+)/', $doc, $matches)) {
                                $method['serverMethod'] = $method['name'];
                                $method['name'] = $matches[1];
                            }                       
                            if(!!preg_match('/' . $this->_formAttribute . '/', $doc)) {
                                $method['formHandler'] = true;
                            }

                            $methods[] = $method;
                        }
                        
                        // Add events support
                        $isEvent = !!preg_match('/' . $this->_eventAttribute . '/', $doc); 
                        if($isEvent) {
                            $event = array(
                                'name' => $rMethod->getName(),
                                'len' => $rMethod->getNumberOfParameters(),
                            );
                            
                            $eventMethods[] = $event;
                        }
                        
                    }
                }
                
                if(count($methods) > 0) {
                    $classes[$class] = $methods;
                }
                  
                if(count($methods) > 0) {
                    $events[$class] = $eventMethods;
                }        
            }
        }
        
        $api = array(
            'url' => $this->_routerUrl,
            'type' => $this->_type,
            'actions' => $classes
            //'events' => $events
        );
        
        if($this->_namespace !== false) {
            $api['namespace'] = $this->_namespace;
        }
        
        $this->_parsedClasses = $this->_classes;
        $this->_parsedAPI = $api;
        
        return $api;
    }
    
    
    
    public function getParsedAPI() {
        return $this->_parsedAPI;
    }
    
    public function getClassPath($class, $settings = false) {
        if(!$settings) {
            $settings = $this->_settings;
        }
        
        if($settings['autoInclude'] === true) {
            $path = $settings['basePath'] . DIRECTORY_SEPARATOR .
                    $settings['subPath'] . DIRECTORY_SEPARATOR .
                    $class . '.php';
            $path = str_replace('\\\\', '\\', $path);            
        } else {
            $rClass = new ReflectionClass($settings['prefix'] . $class);
            $path = $rClass->getFileName();
        }
        
        return $path;
    }
    
    public function getClassesPaths() {
        $classesPaths = array();
        foreach($this->getClasses() as $name => $settings) {
            $classesPaths[] = $this->getClassPath($name, $settings);
        }
        
        return $classesPaths;
    }
    
    public function getClasses() {
        return $this->_classes;
    }
    
    private function _print($api) {
        header('Content-Type: text/javascript');


        echo ($this->_namespace ? 
            'Ext.ns(\'' . substr($this->_descriptor, 0, strrpos($this->_descriptor, '.')) . '\'); ' . $this->_descriptor:
            'Ext.ns(\'Ext.app\'); ' . 'Ext.app.REMOTING_API'
        );
        echo ' = ';
        echo json_encode($api);
        echo ';';
    }
    
    public function setRouterUrl($routerUrl = 'router.php') {
        if(isset($routerUrl)) {
            $this->_routerUrl = $routerUrl;
        }
    }
    
    public function getRouterUrl() {
        return $this->_routerUrl;
    }
    
    public function setCacheProvider($cacheProvider) {
        if($cacheProvider instanceof ExtDirect_CacheProvider) {
            $this->_cacheProvider = $cacheProvider;
        }
    }

    public function getCacheProvider() {
        return $this->_cacheProvider;
    }
        
    public function setRemoteAttribute($attribute) {
        if(is_string($attribute) && strlen($attribute) > 0) {
            $this->_remoteAttribute = $attribute;
        }
    }

    public function getRemoteAttribute() {
        return $this->_remoteAttribute;
    }
 
     public function setDescriptor($descriptor) {
        if(is_string($descriptor) && strlen($descriptor) > 0) {
            $this->_descriptor = $descriptor;
        }
    }

    public function getDescriptor() {
        return $this->_descriptor;
    }
        
    public function setFormAttribute($attribute) {
        if(is_string($attribute) && strlen($attribute) > 0) {
            $this->_formAttribute = $attribute;
        }
    }

    public function getFormAttribute() {
        return $this->_formAttribute;
    }

    public function setNameAttribute($attribute) {
        if(is_string($attribute) && strlen($attribute) > 0) {
            $this->_nameAttribute = $attribute;
        }
    }

    public function getNameAttribute() {
        return $this->_nameAttribute;
    }
               
    public function setNameSpace($namespace) {
        if(is_string($namespace) && strlen($namespace) > 0) {
            $this->_namespace = $namespace;
        }
    }

    public function getNamespace() {
        return $this->_namespace;
    }
        
    public function setDefaults($defaults, $clear = false) {
        if($clear === true) {
            $this->clearDefaults();
        }
        
        if(is_array($defaults)) {
            $this->_defaults = array_merge($this->_defaults, $defaults);
        }
    }
    
    public function getDefaults() {
        return $this->_defaults;    
    }
    
    public function clearDefaults() {
        $this->_defaults = array();
    }
    
    // -- Additional methods
    /**
     * Set type of transport to use, remoting or socket for now
     * 
     * @param string $type Transport type, default remoting. Specity 'socket' for WS Socket
     * 
     * @return void
     */
    public function setTransportType($type) {
        $this->_type = $type;
    }
    
    public function getTransportType() {
        return $this->type;
    }
}
