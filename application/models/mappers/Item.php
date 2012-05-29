<?php

class Application_Model_Mapper_Item extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_Item');
	}
	
	protected function genericSelect() {
		$oDb = $this->getDbTable();
		$oSelect = $oDb->select()->setIntegrityCheck(false)
								 ->from(array('I' => 'ITEMS'), array('I.PER', 'I.DATE_ACHAT', 'L.LIBELLE', 'C.LIBELLE','I.QUANTITE', 'I.ID'))
								 ->joinLeft(array('L' => 'LIBELLES'), 'I.ID_LIBELLE = L.ID', array())
								 ->joinLeft(array('C' => 'CATEGORIES'), 'L.ID_CATEGORIE = C.ID', array());
		return $oSelect;
	}
	
	public function listerToutUtilistateurs() {
		return $this->getDbTable()->fetchAllArray($this->genericSelect());
	}
	

}