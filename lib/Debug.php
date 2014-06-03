<?php
interface debug_Interface {
    public function dump($value = '');
    public function console_log($value = '');
}

class Debug implements debug_Interface {

	public function dump($value = '') {
		$value = var_export($value, true);
		echo '<pre>';
		echo $value;
		echo '</pre>';
	}

	public function console_log($value = '') {
		$_SERVER['argv']['console_log'] = json_encode($value);
	}
}
?>