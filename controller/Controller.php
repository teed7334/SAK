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

	public function setCookie($key = '', $value = '', $expire = 86400) {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		$encryption = $this->lib->make('Encryption');
		$encryption->debug = $this->debug;
		$encryption->import_key(PUBLIC_KEY);
		$value = $encryption->encode($value);

		setcookie($key, $value, time() + $expire);
	}

	public function removeCookie($key = '', $value = '', $expire = 86400) {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		setcookie($key, '', time() - $expire);
	}

	public function getCookie($key) {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		$encryption = $this->lib->make('Encryption');
		$encryption->import_key(PUBLIC_KEY);
		$encryption->debug = $this->debug;
		$value = $encryption->decode($_COOKIE[$key]);

		return $value;
		
	}

	public function setSession($key = '', $value = '') {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		$encryption = $this->lib->make('Encryption');
		$encryption->import_key(PUBLIC_KEY);
		$encryption->debug = $this->debug;
		$value = $encryption->encode($value);

		$_SESSION[$key] = $value;
	}

	public function removeSession($key = '', $value = '') {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		unset($_SESSION[$key]);
	}

	public function getSession($key) {

		if($key == '') {
			return $this->debug ? array('message' => 'Is null', 'status' => false, 'value' => array('key' => $key)) : false;
		}

		$encryption = $this->lib->make('Encryption');
		$encryption->import_key(PUBLIC_KEY);
		$encryption->debug = $this->debug;
		$value = $encryption->decode($_SESSION[$key]);

		return $value;
		
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