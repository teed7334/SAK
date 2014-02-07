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

	public function getClientIP() {

		try {

			$ip = false;

			if(getenv('HTTP_CLIENT_IP')) {
			    $ip = getenv('HTTP_CLIENT_IP');
			} else if(getenv('HTTP_X_FORWARDED_FOR')) {
			    $ip = getenv('HTTP_X_FORWARDED_FOR');
			} else if(getenv('HTTP_X_FORWARDED')) {
			    $ip = getenv('HTTP_X_FORWARDED');
			} else if(getenv('HTTP_FORWARDED_FOR')) {
			    $ip = getenv('HTTP_FORWARDED_FOR');
			} else if(getenv('HTTP_FORWARDED')) {
			    $ip = getenv('HTTP_FORWARDED');
			} else if(getenv('REMOTE_ADDR')) {
			    $ip = getenv('REMOTE_ADDR');
			}

			return $ip;
		
		} catch(Exception $e) {

		}

	}

	public function setCookie($key = '', $value = '') {

		try {

			if($key == '') {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}
			
			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			setcookie($key, $encryption->encode($value), time() + 86400);

			return true;

		} catch(Exception $e) {

		}

	}

	public function getCookie($key = '') {

		try {
			
			if($key == '' || !isset($_COOKIE[$key])) {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);

			return $encryption->decode($_COOKIE[$key]);
			
		} catch(Exception $e) {

		}

	}

	public function removeCookie($key = '') {

		try {
			
			if($key == '' || !isset($_COOKIE[$key])) {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}

			setcookie($key, '', time() - 86400);

			return true;
			
		} catch(Exception $e) {

		}

	}

	public function setSession($key = '', $value = '') {

		try {

			if($key == '') {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}
			
			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			$_SESSION[$key] = $encryption->encode($value);

			return true;

		} catch(Exception $e) {

		}

	}

	public function getSession($key = '') {

		try {
			
			if($key == '' || !isset($_SESSION[$key])) {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			return $encryption->decode($_SESSION[$key]);
			
		} catch(Exception $e) {

		}

	}

	public function removeSession($key = '') {

		try {
			
			if($key == '' || !isset($_SESSION[$key])) {
				return $this->debug ? array('message' => "undefined index {$key}", 'status' => false, 'value' => array('key' => $key)) : false;
			}

			unset($_SESSION[$key]);

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