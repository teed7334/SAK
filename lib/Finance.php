<?php 
class Finance {

	protected $debug = false;

	public function debug($debug = false) {
        $this->debug = $debug;
    }
	
	public function floor($value = 0, $precision = 0) {

		$t = 1;

		for($i = 0; $i < $precision; $i++) {
			$t *= 10;
		}

		$value = doubleval($value) * $t;
		$value = floor($value);
		$value = $value / $t;

		return $value;

	}

	public function ceil($value = 0, $precision = 0) {

		$t = 1;

		for($i = 0; $i < $precision; $i++) {
			$t *= 10;
		}

		$value = doubleval($value) * $t;
		$value = ceil($value);
		$value = $value / $t;

		return $value;

	}

	public function exchange_rate($from = '', $to = '') {

		try {

			if($from == '' || $to == '') {
				throw new Exception('Is null');
			}

			$uri = "http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s={$from}{$to}=X";  
			$rate = file_get_contents($uri);  
			$rate = explode(',', $rate);  
			
			if(count($rate) < 2) {
				throw new Exception("Can't get exchange rate");
			}

			return $rate;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('from' => $from, 'to' => $to)) : false;
		}

	}

}
?>