<?php
    class File {
        /**
         * @remotable
         * @remoteName list
         */
    	public function listFiles($folder){
    	    if(substr($folder, 0, 3) === '../') {
    	        return 'Nice try buddy';
    	    }
    	    return array_slice(scandir($folder), 2);
    	}
        
        /**
         * @remotable
         * @formHandler
         */
        public function add($post, $files){
            if($files && isset($files[$post['formField']])) {
                $file = $files[$post['formField']];
                move_uploaded_file($file['tmp_name'], 'data/' . $file['name']);
                return pathinfo('data/' . $file['name']);
            }            
        }
    }
?>
