<?php

class Application_Model_Mapper_Produits extends Projet_Mapper {
	
	
	const LETTRE_FRAIS	= 'F';
	const LETTRE_NONFRAIS	= 'NF';
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_Produits');
	}
	
	protected function genericSelect() {
		$oDb = $this->getDbTable();
		$oSelect = $oDb->select()->setIntegrityCheck(false)
								 ->from(array('P' => 'PRODUITS'), array($this->formatDate('P.PER'), $this->formatDateTime('P.DATE_ACHAT'), 'RI.LIBELLE', 'RC.LIBELLE',"CONCAT_WS( ' ',P.QUANTITE , RM.UNITE )", 'P.ID'))
								 ->joinLeft(array('RI' => 'REF_ITEMS'), 'P.ID_LIBELLE = RI.ID', array())
								 ->joinLeft(array('RC' => 'REF_CATEGORIES'), 'RI.ID_CATEGORIE = RC.ID', array())
								 ->joinLeft(array('RM' => 'REF_MESURES'), 'RM.ID = RI.ID_MESURE', array());
		return $oSelect;
	}
	
	public function listerFraisToutUtilisateurs() {
		$oSelect = $this->genericSelectForTypeCategorie()->where('RCO.ABREGE =?', self::LETTRE_FRAIS);
		return $this->getDbTable()->fetchAllArray($oSelect);
	}
	
	public function listerNonFraisToutUtilisateurs() {
		$oSelect = $this->genericSelectForTypeCategorie()->where('RCO.ABREGE =?', self::LETTRE_NONFRAIS);
		return $this->getDbTable()->fetchAllArray($oSelect);
	}
	
	protected function genericSelectForTypeCategorie() {
		$oSelect = $this->genericSelect();
		$oSelect->joinLeft(array('LIC' => 'L_ITEMS_CONSERVATIONS'), 'LIC.ID_ITEM = RI.ID', array())
				->joinLeft(array('RCO' => 'REF_CONSERVATIONS'), 'RCO.ID = LIC.ID_CONSERVATION', array());
		
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