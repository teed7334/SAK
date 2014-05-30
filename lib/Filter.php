<?php 
interface filter_Interface {
    public function string ($string = '');
    public function email ($string = '');
    public function uri ($string = '');
}

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
