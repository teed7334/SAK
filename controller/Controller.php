<?php
class Controller {

	protected $model = '';
	protected $view = '';
	protected $lib = '';
	protected $data = array();
	protected $debug = false;

	public function __construct() {

		try {

			$this->lib = new Factory();
			$this->lib->setDirectory(LIB);
			$this->model = new Factory();
			$this->model->setDirectory(MODEL);

		} catch(Exception $e) {

        }

	}

	public function debug($debug = false) {
		$this->debug = $debug;
	}

	public function setUserData($key = '', $value = array()) {

		try {

			if($key == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->import_key(PRIVATE_KEY);
			
			$_key = $encryption->inreversible_encode($value, PUBLIC_KEY);
			$_value = $encryption->encode($value);
			setcookie($key, $_key, time() + 86400);
			$_SESSION[$_key] = $_value;

			return true;

		} catch(Exception $e) {

        }

	}

	public function getUserData($key = '') {

		try {

			if($key == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
			}

			if(!isset($_COOKIE[$key])) {
				return $this->debug ? array('message' => "Undefined index: {$key}", 'status' => false, 'value' => array('key' => $key)) : false;	
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->import_key(PRIVATE_KEY);

			$data = $encryption->decode($_SESSION[$_COOKIE[$key]]);

			return $data;

		} catch(Exception $e) {

        }

	}

	public function removeUserData($key = '') {

		try {

			if($key == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
			}		

			if(!isset($_COOKIE[$key])) {
				return $this->debug ? array('message' => "Undefined index: {$key}", 'status' => false, 'value' => array('key' => $key)) : false;	
			}

			unset($_SESSION[$_COOKIE[$key]]);
			setcookie($key, '', time() - 86400);

			return true;

		} catch(Exception $e) {

        }
        
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

	public function view($view = '') {

		try {

			if($view == '') {
	            return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => '') : false;
	        }

			$view = VIEW . "/{$view}.php";

			if(!file_exists($view)) {
	            return $this->debug ? array('message' => "Failed opening '{$view}' for inclusion", 'status' => false, 'value' => $view) : false;
	        }

	        include_once($view);
	        echo "\n";

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
	        echo "\n";

	        return true;

    	} catch(Exception $e) {

        }

    }

	public function js_assign($key = '', $value = '') {

		try {

			if($key == '' || $value == '') {
				return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
			}

			$_SERVER['js_controller'][$key] = "" . $value;

			return true;

		} catch(Exception $e) {

        }			

	}

}
?>
