<?php
class Debug {

	public function dump($value = '') {
		$value = var_export($value, true);
		echo '<pre>';
		echo $value;
		echo '</pre>';
	}

	public function console_log($value = '') {
		$_SERVER['js_controller']['console_log'] = json_encode($value);
	}
}
?>