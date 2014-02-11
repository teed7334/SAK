<?php
class Model {

	protected $mysql = NULL;
	protected $debug = false;

	public function __construct() {
		$lib = new Factory();
		$lib->setDirectory(LIB);
		$this->mysql = $lib->make('MySQL'); 
		$this->mysql->setAdapter(HOST, ACCOUNT, PASSWORD, DATABASE);
	}

	public function debug($debug = false) {
		$this->debug = $debug;
		$this->mysql->debug($this->debug);
	}

}
?>