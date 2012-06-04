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
	
	public function setLibelle($sRefLibelle) {
		$this->_libelle = $sRefLibelle;
	}
	
	public function getLibelle() {
		return $this->_libelle;
	}
}