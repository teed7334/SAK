<?php
class Model {

	protected $mysql = NULL;

	public function __construct() {

		$this->mysql = $_SERVER['MYSQL'];
		unset($_SERVER['MYSQL']);
		
	}

}
?>