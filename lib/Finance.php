<?php 
interface finance_Interface {
    public function debug($debug = false);
    public function floor($value = 0, $precision = 0);
    public function ceil($value = 0, $precision = 0);
    public function exchange_rate($from = '', $to = '');
}

class Finance implements finance_Interface {

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

			if('' === (string) trim($from) || '' === (string) trim($to)) {
				throw new Exception('Is null');
			}

			$uri = "http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s={$from}{$to}=X";  
			$rate = file_get_contents($uri);  
			$rate = explode(',', $rate);  
			
			if(2 > count($rate)) {
				throw new Exception("Can't get exchange rate");
			}

			return $rate;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('from' => $from, 'to' => $to)) : false;
		}

	}

}
?>