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
			curl_setopt_array($options);
			$result = curl_exec($ch);

			if(!$result) {
				return $this->debug ? array('message' => curl_error($ch), 'status' => false, 'value' => array('url' => $url, 'params' => $params)) : false;
			}

			curl_close($ch);

			return $result;

		} catch(Exception $e) {

		}

	}

	public function post($url = '', $params = array(), $options = array()) {

		try {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt_array($options);
			$result = curl_exec($ch);

			if(!$result) {
				return $this->debug ? array('message' => curl_error($ch), 'status' => false, 'value' => array('url' => $url, 'params' => $params)) : false;
			}

			curl_close($ch);

			return $result;

		} catch(Exception $e) {

		}

	}
}
?>