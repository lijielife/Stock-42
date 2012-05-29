<?php

class Application_Model_Item extends Projet_Entite {

	protected $_idLibelle = null;
	protected $_quantite = null;
	protected $_idUser = null;
	protected $_per = null;
	
	public function setIdLibelle($nIdLibelle) {
		$this->_idLibelle = $nIdLibelle;
	}
	
	public function getIdLibelle() {
		return $this->_idLibelle;
	}
	
	public function setQuantite($nQuantite) {
		$this->_quantite = $nQuantite;
	}
	
	public function getLibelle() {
		return $this->_quantite;
	}
	
	public function setPer($_Per) {
		$this->_per = $_Per;
	}
	
	public function getLibelle() {
		return $this->_per;
	}
	
}