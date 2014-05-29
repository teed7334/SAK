<?php
class example extends Controller {

	protected $CURL = '';
	protected $Debug = '';
	protected $Encryption = '';
	protected $Filter = '';
	protected $Finance = '';

	public function __construct() {
		parent::__construct();
		$this->CURL = $this->lib->make('CURL');
		$this->CURL->debug(true);
		$this->Debug = $this->lib->make('Debug');
		$this->Encryption = $this->lib->make('Encryption');
		$this->Encryption->debug(true);
		$this->Filter = $this->lib->make('Filter');
		$this->Finance = $this->lib->make('Finance');
		$this->Finance->debug(true);
	}
	
	public function action_index() {
		
	}

	public function action_curl_get() {
		$url = 'http://www.google.com/search';
		var_dump($this->CURL->get($url, array('q' => 1234)));
	}

	public function action_curl_post() {
		$url = 'http://www.google.com/search';
		var_dump($this->CURL->post($url, array('q' => 1234)));
	}

	public function action_debug_dump() {
		$object = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		$this->Debug->dump($object);
	}

	public function action_debug_console_log() {
		$object = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		$this->Debug->console_log($object);
	}

	public function action_encryption_encode() {
		$object = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		var_dump($this->Encryption->encode($object));
	}

	public function action_encryption_decode() {
		$object = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		var_dump(
			$this->Encryption->decode(
				$this->Encryption->encode($object)));
	}

	public function action_encryption_token() {
		$object = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		var_dump($this->Encryption->token($object));
	}

	public function action_filter_string() {
		var_dump($this->Filter->string('test'));
	}

	public function action_filter_email() {
		var_dump($this->Filter->email('hostmaster@sak.cc@'));
	}

	public function action_filter_uri() {
		var_dump($this->Filter->uri('http://www.google.com'));
	}

	public function action_finance_floor() {
		var_dump($this->Finance->floor(0.005, 2));
	}

	public function action_finance_ceil() {
		var_dump($this->Finance->ceil(0.005, 2));
	}

	public function action_finance_exchange_rate() {
		var_dump($this->Finance->exchange_rate('TWD', 'USD'));
	}
}
