<?php
class Debug {

	public function dump($value = '') {

		try {

			$value = var_export($value, true);
			echo '<pre>';
			echo $value;
			echo '</pre>';

		} catch(Exception $e) {
        
        }

	}

	public function console_log($value = '') {

		try {

			$_SERVER['js_controller']['console_log'] = json_encode($value);

		} catch(Exception $e) {
        
        }

	}

}
?>