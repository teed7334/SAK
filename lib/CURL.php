<?php 
class CURL {
	
	protected $debug = false;

	public function debug($debug = false) {
        $this->debug = $debug;
    }

	public function get($url = '', $params = array()) {

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
			$result = curl_exec($ch);
			curl_close($ch);

			if(!$result) {
				return $this->debug ? array('message' => "http get error", 'status' => false, 'value' => array('url' => $str, 'params' => $params)) : false;
			}

			return $result;

		} catch(Exception $e) {

		}

	}

	public function post($url = '', $params = array()) {

		try {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			$result = curl_exec($ch);
			curl_close($ch);

			if(!$result) {
				return $this->debug ? array('message' => "http post error", 'status' => false, 'value' => array('url' => $str, 'params' => $params)) : false;
			}

			return $result;

		} catch(Exception $e) {

		}
		
	}
}
?>