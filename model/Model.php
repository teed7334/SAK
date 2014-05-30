<?php
interface model_Interface {
    public function debug($debug = false);
}

class Model implements model_Interface {

	protected $mysql = NULL;
    protected $lib = NULL;
    protected $debug = false;

	public function __construct() {
		$this->lib = new Factory();
		$this->lib->setDirectory(LIB);
		$this->mysql = $this->lib->make('MySQL'); 
		$this->mysql->setAdapter(HOST, ACCOUNT, PASSWORD, DATABASE);
	}

	public function debug($debug = false) {
		$this->debug = $debug;
		$this->mysql->debug($this->debug);
	}

}
?>