<?php

class Application_Model_Mapper_RefItems extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_RefItems');
	}
	
	public function getLibelleByIdCategorie($nIdCategorie = null) {
		if ($nIdCategorie === null) {
			return array(0 => 'aucune categorie fournie');
		}
		$oDb = $this->getDbTable();
		$oSelect = $oDb->select()->from(array('RL' => 'REF_ITEMS'), array('RL.ID', 'RL.LIBELLE'))
								 ->where('RL.ID_CATEGORIE =?', $nIdCategorie);
		return $oDb->fetchPairs($oSelect);
	}
	
	
	

}