<?php 
class CURL {
	
	protected $debug = false;

	public function debug($debug = false) {
        $this->debug = $debug;
    }

	public function get($url = '', $params = array(), $options = array()) {

		try {

			$ch = curl_init();

			$query = '';

			foreach($params as $key => $value) {
				if($query == '') {
					$query = "?{$key}={$value}";
				} else {
					$query .= "&{$key}={$value}";
				}
			}

			curl_setopt($ch, CURLOPT_URL, "{$url}{$query}");
			if(count($options) > 0) {
				curl_setopt_array($options);
			}
			$result = curl_exec($ch);

			if(!$result) {
				throw new Exception(curl_error($ch));
			}

			curl_close($ch);

			return $result;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('url' => $url, 'params' => $params, 'options' => $options)) : false;
		}

	}

	public function post($url = '', $params = array(), $options = array()) {

		try {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			if(count($options) > 0) {
				curl_setopt_array($options);
			}
			$result = curl_exec($ch);

			if(!$result) {
				throw new Exception(curl_error($ch));
			}

			curl_close($ch);

			return $result;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('url' => $url, 'params' => $params, 'options' => $options)) : false;
		}

	}
}
?>