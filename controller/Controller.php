<?php
class Controller {

	protected $model = '';
	protected $view = '';
	protected $lib = '';
	protected $data = array();
	protected $debug = false;

	public function __construct() {
		$this->lib = new Factory();
		$this->lib->setDirectory(LIB);
		$this->model = new Factory();
		$this->model->setDirectory(MODEL);
	}

	public function debug($debug = false) {
		$this->debug = $debug;
	}

	public function securityString($string = '', $start = 0, $length = 0, $encode = 'UTF-8') {
		return mb_substr(strip_tags($string), $start, $length, $encode);
	}

	public function getClientIP() {
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
	}

	public function setCookie($key = '', $value = '') {

		try {

			if($key == '') {
				throw new Exception("undefined index {$key}");
			}
			
			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			setcookie($key, $encryption->encode($value), time() + 86400);

			return true;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
		}

	}

	public function getCookie($key = '') {

		try {
			
			if($key == '' || !isset($_COOKIE[$key])) {
				throw new Exception("undefined index {$key}");
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);

			return $filter->string($_COOKIE[$key]);
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key)) : false;
		}

	}

	public function removeCookie($key = '') {

		try {
			
			if($key == '' || !isset($_COOKIE[$key])) {
				throw new Exception("undefined index {$key}");
			}

			setcookie($key, '', time() - 86400);

			return true;
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key)) : false;
		}

	}

	public function setSession($key = '', $value = '') {

		try {

			if($key == '') {
				throw new Exception("undefined index {$key}");
			}
			
			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			$_SESSION[$key] = $encryption->encode($value);

			return true;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
		}

	}

	public function getSession($key = '') {

		try {
			
			if($key == '' || !isset($_SESSION[$key])) {
				throw new Exception("undefined index {$key}");
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);
			return $encryption->decode($_SESSION[$key]);
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key)) : false;
		}

	}

	public function removeSession($key = '') {

		try {
			
			if($key == '' || !isset($_SESSION[$key])) {
				throw new Exception("undefined index {$key}");
			}

			unset($_SESSION[$key]);

			return true;
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key)) : false;
		}

	}

	public function assign($key = '', $value = '') {

		try {

			if($key == '' || $value == '') {
				throw new Exception('Is null');
			}

			$this->data[$key] = $value;

			return true;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
        }

	}

	public function view($view = '') {

		try {

			if($view == '') {
				throw new Exception('Is null');
	        }

			$view = VIEW . "/{$view}.php";

			if(!file_exists($view)) {
				throw new Exception("Failed opening '{$view}' for inclusion");
	        }

	        include_once($view);
	        echo "\n";

	        return true;

    	} catch(Exception $e) {
    		return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('view' => $view)) : false;
        }

	}

	public function element($element = '') {

		try {

			if($element == '') {
				throw new Exception('Is null');
	        }

			$element = ELEMENT . "/{$element}.php";

			if(!file_exists($element)) {
				throw new Exception("Failed opening '{$element}' for inclusion");
	        }

	        include_once($element);
	        echo "\n";

	        return true;

    	} catch(Exception $e) {
    		return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('element' => $element)) : false;
        }

    }

	public function js_assign($key = '', $value = '') {

		try {

			if($key == '' || $value == '') {
				throw new Exception('Is null');
			}

			$_SERVER['js_controller'][$key] = "" . $value;

			return true;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
        }			

	}

}
?>