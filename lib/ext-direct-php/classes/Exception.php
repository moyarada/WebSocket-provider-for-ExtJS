<?php
    class Class_Exception {
        /**
         * @remotable
         */
    	public function makeError(){
    		throw new Exception('A server-side thrown exception');
    	}
    }
?>
