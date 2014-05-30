<?php
interface controller_Interface {
    public function debug($debug = false);
    public function getClientIP();
    public function setCookie($key = '', $value = '');
    public function getCookie($key = '', $filter = FALSE);
    public function removeCookie($key = '');
    public function setSession($key = '', $value = '');
    public function getSession($key = '', $filter = FALSE);
    public function removeSession($key = '');
    public function assign($key = '', $value = '');
    public function view($view = '');
    public function element($element = '');
    public function js_assign($key = '', $value = '');
}

class Controller implements controller_Interface {

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

	public function getClientIP() {
		$ip = false;

		if(getenv('HTTP_CLIENT_IP')) {
		    $ip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
		    $ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('HTTP_X_FORWARDED')) {
		    $ip = getenv('HTTP_X_FORWARDED');
		} elseif(getenv('HTTP_FORWARDED_FOR')) {
		    $ip = getenv('HTTP_FORWARDED_FOR');
		} elseif(getenv('HTTP_FORWARDED')) {
		    $ip = getenv('HTTP_FORWARDED');
		} elseif(getenv('REMOTE_ADDR')) {
		    $ip = getenv('REMOTE_ADDR');
		}

		return $ip;
	}

	public function setCookie($key = '', $value = '') {

		try {

			if('' === (string) trim($key)) {
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

	public function getCookie($key = '', $filter = FALSE) {

		try {
			
			if( '' === (string) trim($key) || !isset($_COOKIE[$key])) {
				throw new Exception("undefined index {$key}");
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);

			if($filter) {
				$Filter = $this->lib->make('Filter');
			}

			return $filter ? $Filter->string($_COOKIE[$key]) : $_COOKIE[$key];
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'filter' => $filter)) : false;
		}

	}

	public function removeCookie($key = '') {

		try {
			
			if('' === (string) trim($key) || !isset($_COOKIE[$key])) {
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

			if('' === (string) trim($key)) {
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

	public function getSession($key = '', $filter = FALSE) {

		try {
			
			if('' === (string) trim($key) || !isset($_SESSION[$key])) {
				throw new Exception("undefined index {$key}");
			}

			$encryption = $this->lib->make('Encryption');
			$encryption->debug($this->debug);

			if($filter) {
				$Filter = $this->lib->make('Filter');
			}

			return $filter ? $Filter->string($_SESSION[$key]) : $_SESSION[$key];
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'filter' => $filter)) : false;
		}

	}

	public function removeSession($key = '') {

		try {
			
			if('' === (string) trim($key) || !isset($_SESSION[$key])) {
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

			if('' === (string) trim($key) || '' === (string) trim($value)) {
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

			if('' === (string) trim($view)) {
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

			if('' === (string) trim($element)) {
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

			if('' === (string) trim($key) || '' === (string) trim($value)) {
				throw new Exception('Is null');
			}

			$_SERVER['argv']['js_assign'][$key] = "" . $value;

			return true;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('key' => $key, 'value' => $value)) : false;
        }			

	}

}
?>