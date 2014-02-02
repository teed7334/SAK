<?php
class Model {

	protected $mysql = NULL;
	protected $debug = false;

	public function __construct() {

		try {

			$lib = new Factory();
			$lib->setDirectory(LIB);
			$this->mysql = $lib->make('MySQL'); 
			$this->mysql->setAdapter(HOST, ACCOUNT, PASSWORD, DATABASE);

		} catch(Exception $e) {

        }
		
	}

	public function debug($debug = false) {
		$this->debug = $debug;
		$this->mysql->debug($this->debug);
	}

}
?>