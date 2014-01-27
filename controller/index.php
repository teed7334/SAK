<?php
class index extends Controller {
	
	public function action_index() {
		$this->assign('hello', 'Hello World!!!!');
		$this->element('all.html41.header');
		$this->view('index');
		$this->element('all.html41.footer');
	}

}