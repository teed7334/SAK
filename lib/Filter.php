<?php 
class filter {
	
	public function string ($string = '') {
		return (string) filter_var($string, FILTER_SANITIZE_STRING);
	}

	public function email ($string = '') {
		return (string) filter_var($string, FILTER_SANITIZE_EMAIL);
	}

	public function uri ($string = '') {
		return (string) filter_var($string, FILTER_SANITIZE_URL);	
	}
	
}
?>
