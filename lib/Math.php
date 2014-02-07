<?php 
class Math {
	
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

}
?>