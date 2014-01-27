<?php
class Model {

	protected $mysql = NULL;

	public function __construct() {

		try {

			$lib = new Factory();
			$lib->setDirectory(LIB);
			$this->mysql = $lib->make('MySQL'); 
			$this->mysql->setAdapter(HOST, ACCOUNT, PASSWORD, DATABASE);

		} catch(Exception $e) {

        }
		
	}

}
?>