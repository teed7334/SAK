<?php
interface factory_Interface {
    public function setDirectory($path = '');
    public function debug($debug = false);
    public function make($class_name = '');
}

class Factory implements factory_Interface {
        
    protected $path = '';
    protected $resource = array();
    protected $debug = false;

    public function setDirectory($path = '') {

        try {

            if(!is_dir($path)) {
                throw new Exception("Directory '{$path}' not found");
            }

            $this->path = $path;

            return true;

        } catch(Exception $e) {
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('path' => $path)) : false;
        }

    }

    public function debug($debug = false) {
        $this->debug = $debug;
    }

    public function make($class_name = '') {

        try {

    		$file = "{$this->path}/{$class_name}.php";

            if(!file_exists($file)) {
                throw new Exception("Failed opening '{$file}' for inclusion");
            }
            
            if(!in_array($class_name, $this->resource)) {
            	include_once(realpath($file));
            	$this->resource[] = $class_name;
        	}

            if(!class_exists($class_name)) {
                throw new Exception("Class '{$class_name}' not found");
            }
            
            eval('$object = ' . "new {$class_name}();");
            
            return $object;

        } catch(Exception $e) {
            return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('class_name' => $class_name)) : false;
        }
        
    }
}
?>
