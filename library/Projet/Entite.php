<?php

class Projet_Entite extends ArrayObject {
	
	protected $_id = null;
	
	public function getId() {
		return $this->_id;
	}
	
	public function setId($id) {
		$this->_id = $id;
	}
	
}