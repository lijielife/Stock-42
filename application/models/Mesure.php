<?php

class Application_Model_Conservation extends Projet_Entite {

	protected $_unite = null;
	protected $_libelle = null;
	
	public function setUnite($sUnite) {
		$this->_unite = $sUnite;
	}
	
	public function getUnite() {
		return $this->_unite;
	}
	
	public function setLibelle($sRefLibelle) {
		$this->_libelle = $sRefLibelle;
	}
	
	public function getLibelle() {
		return $this->_libelle;
	}
}