<?php

class Application_Model_Item extends Projet_Entite {

	protected $_description = null;
	protected $_libelle = null;
	protected $_idCategorie = null;
	protected $_idMesure	= null;
	
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
	
	public function setIdCategorie($nIdCategorie) {
		$this->_idCategorie = $nIdCategorie;
	}
	
	public function getIdCategorie() {
		return $this->_idCategorie;
	}
	
	public function setIdMesure($nIdMesure) {
		$this->_idMesure = $nIdMesure;
	}
	
	public function getIdMesure() {
		return $this->_idMesure;
	}
	
	
}