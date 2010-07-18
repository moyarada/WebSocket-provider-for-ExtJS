<?php

class ExtDirect_CacheProvider {
    private $_filePath = null;
    private $_cache = false;
    
    public function __construct($filePath) {
        if(is_string($filePath)) {
            $this->_filePath = $filePath;
            
            if(!file_exists($filePath) && !touch($filePath)) {
                throw new Exception('Unable to create or access ' . $filePath);
            }
        }
    }
    
    public function getAPI() {
        $this->_parse();        
        return $this->_cache['api'];
    }
    
    public function isModified($apiInstance) {
        $this->_parse();
        if(!$apiInstance instanceof ExtDirect_API) {
            throw new Exception('You have to pass an instance of ExtDirect_API to isModified function');
        }
        
        return !(
            $apiInstance->isEqual($this->_cache['classes'], $apiInstance->getClasses()) &&
            // even if the classes are the same we still have to check if they have been modified
            $apiInstance->isEqual($this->_cache['modifications'], $this->_getModifications($apiInstance))
        );
    }

    public function save($apiInstance) {
        if(!$apiInstance instanceof ExtDirect_API) {
            throw new Exception('You have to pass an instance of ExtDirect_API to save function');
        }
                
        $cache = json_encode(array(
            'classes' => $apiInstance->getClasses(),
            'api' => $apiInstance->getAPI(),
            'modifications' => $this->_getModifications($apiInstance)
        ));
        
        $this->_write($cache);  
    }
    
    private function _getModifications($apiInstance) {
        if(!$apiInstance instanceof ExtDirect_API) {
            throw new Exception('You have to pass an instance of ExtDirect_API to _getModifications function');
        }
        
        $modifications = array();
        $classesPaths = $apiInstance->getClassesPaths();
        
        foreach($classesPaths as $path) {
            if(file_exists($path)) {
                $modifications[$path] = filemtime($path);
            }
        }
        
        return $modifications;
    }
    
    private function _write($content = '', $append = false) {
        file_put_contents($this->_filePath, $content, $append ? FILE_APPEND : 0);
    }
    
    private function _parse() {
         if($this->_cache === false) {
             $content = file_get_contents($this->_filePath);
             if(strlen($content) === 0) {
                 $this->_cache = array(
                     'classes' => array(), 
                     'api' => array(), 
                     'modifications' => array()
                 );
                 return;
             }
            
             $this->_cache = json_decode($content, true);            
        }       
    }
}