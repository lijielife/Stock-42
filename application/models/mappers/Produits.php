<?php

class Application_Model_Mapper_Produits extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_Produits');
	}
	
	protected function genericSelect() {
		$oDb = $this->getDbTable();
		$oSelect = $oDb->select()->setIntegrityCheck(false)
								 ->from(array('P' => 'PRODUITS'), array($this->formatDate('P.PER'), $this->formatDateTime('P.DATE_ACHAT'), 'RL.LIBELLE', 'RC.LIBELLE','P.QUANTITE', 'P.ID'))
								 ->joinLeft(array('RL' => 'REF_ITEMS'), 'P.ID_LIBELLE = RL.ID', array())
								 ->joinLeft(array('RC' => 'REF_CATEGORIES'), 'RL.ID_CATEGORIE = RC.ID', array());
		return $oSelect;
	}
	
	public function listerToutUtilistateurs() {
		return $this->getDbTable()->fetchAllArray($this->genericSelect());
	}
	
	
	public function getDataArray(Application_Model_Produit $oEntite) {
		$aData['ID_LIBELLE'] = $oEntite->getIdLibelle();
		$aData['QUANTITE'] = $oEntite->getQuantite();
		$aData['PER'] = $oEntite->getPer();
		return $aData;
	}

}