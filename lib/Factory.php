<?php
class Factory {
        
    protected $path = '';
    protected $resource = array();
    protected $debug = false;

    public function setDirectory($path = '') {

        try {

            if($path == '') {
                return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => $path) : false;
            }

            if(!is_dir($path)) {
                return $this->debug ? array('message' => "Directory '{$path}' not found", 'status' => false, 'value' => $path) : false;
            }

            $this->path = $path;

            return true;

        } catch(Exception $e) {

        }

    }

    public function debug($debug = false) {
        $this->debug = $debug;
    }

    public function make($class_name = '') {

        try {

    		$file = "{$this->path}/{$class_name}.php";

            if(!file_exists($file)) {
                return $this->debug ? array('message' => "Failed opening '{$file}' for inclusion", 'status' => false, 'value' => $file) : false;
            }
            
            if(!in_array($class_name, $this->resource)) {
            	include_once(realpath($file));
            	$this->resource[] = $class_name;
        	}

            if(!class_exists($class_name)) {
                return $this->debug ? array('message' => "Class '{$class_name}' not found", 'status' => false, 'value' => $class_name) : false;
            }
            
            eval('$object = ' . "new {$class_name}();");
            
            return $object;

        } catch(Exception $e) {

        }
        
    }
}
?>