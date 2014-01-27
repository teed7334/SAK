<?php
class index extends Controller {
	
	public function action_index() {
		$this->element('all.html41.header');
		$this->view('index');
	}

}