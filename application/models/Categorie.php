<?php

class Application_Model_Categorie extends Projet_Entite {

	protected $_description = null;
	protected $_libelle = null;
	
	public function setDescription($sDescription) {
		$this->_description = $sDescription;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	public function setLibelle($sLibelle) {
		$this->_libelle = $sLibelle;
	}
	
	public function getLibelle() {
		return $this->_libelle;
	}
}