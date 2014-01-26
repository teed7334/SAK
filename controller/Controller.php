<?php
class Controller {

	protected $model = '';
	protected $view = '';
	protected $lib = '';
	protected $data = array();
	protected $debug = false;

	public function __construct() {

		try {

			$this->model = $_SERVER['MODEL'];
			$this->lib = $_SERVER['LIB'];
			unset($_SERVER['MODEL']);
			unset($_SERVER['LIB']);

		} catch(Exception $e) {

        }

	}

	public function debug($debug = false) {
		$this->debug = $debug;
	}

	public function assign($key = '', $value = '') {

		try {

			if($key == '' || $value == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
			}

			$this->data[$key] = $value;

			return true;

		} catch(Exception $e) {

        }

	}

	public function render($view = '') {

		try {

			if($view == '') {
	            return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => '') : false;
	        }

			$view = VIEW . "/{$view}.php";

			if(!file_exists($view)) {
	            return $this->debug ? array('message' => "Failed opening '{$view}' for inclusion", 'status' => false, 'value' => $view) : false;
	        }

	        include_once($view);

	        return true;

    	} catch(Exception $e) {

        }

	}

	public function element($element = '') {

		try {

			if($element == '') {
	            return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => '') : false;
	        }

			$element = ELEMENT . "/{$element}.php";

			if(!file_exists($element)) {
	            return $this->debug ? array('message' => "Failed opening '{$element}' for inclusion", 'status' => false, 'value' => $element) : false;
	        }

	        include_once($element);

	        return true;

    	} catch(Exception $e) {

        }

    }

	public function js_assign($key = '', $value = '') {

		try {

			if($key == '' || $value == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
			}

			$_SERVER['js_controller'][$key] = $value;

			return true;

		} catch(Exception $e) {

        }			

	}

}
?>