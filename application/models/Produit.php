<?php

class Application_Model_Produit extends Projet_Entite {

	protected $_idRefLibelle = null;
	protected $_quantite = null;
	protected $_idUser = null;
	protected $_per = null;
	
	public function setIdLibelle($nIdRefLibelle) {
		$this->_idRefLibelle = $nIdRefLibelle;
	}
	
	public function getIdLibelle() {
		return $this->_idRefLibelle;
	}
	
	public function getQuantite() {
		return $this->_quantite;
	}
	public function setQuantite($nQuantite) {
		$this->_quantite = $nQuantite;
	}
	
	public function getRefLibelle() {
		return $this->_quantite;
	}
	
	public function setPer($_Per) {
		$this->_per = $_Per;
	}
	
	public function getPer() {
		return $this->_per;
	}
	
}