<?php
    class Time {
        /**
         * @remotable
         */
    	public function get(){
    		return date('m-d-Y H:i:s');
    	}
    }
?>
