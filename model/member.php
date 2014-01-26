<?php
class member extends Model {

	public $_name = 'member';

	public function getMembers() {
		$this->mysql->table($this->_name);
		return $this->mysql->find();
	}

}
?>