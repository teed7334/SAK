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

	public function float ($string = '') {
		return (float) filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT);
	}

	public function integer ($string = '') {
		return (int) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
	}

	public function ipv4 ($string = '') {
		return (string) filter_var($string, FILTER_FLAG_IPV4);	
	}

	public function ipv6 ($string = '') {
		return (string) filter_var($string, FILTER_FLAG_IPV6);	
	}
}
?>