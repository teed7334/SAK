<?php
class index extends Controller {
	
	public function action_index() {
		$this->assign('hello', 'Hello World!!!!');
        var_dump($_SERVER);
		$this->layout2();
	}

	public function layout1() {
		$this->element('all.html41.header');
		$this->view('index');
		$this->element('all.html41.footer');
	}

	public function layout2() {
		$this->element('all.html5.header');
		$this->view('index');
		$this->element('all.html5.footer');
	}	

}