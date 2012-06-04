<?php

class Application_Model_Mapper_RefCategories extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_RefCategories');
	}
	
	public function getLibellesReels() {
		$oSelect = $this->getDbTable()->select()->setIntegrityCheck(false)
												->from(array('RC' => 'REF_CATEGORIES'), array('RC.ID', 'RC.LIBELLE'))
												->join(array('RI' => 'REF_ITEMS'), 'RC.ID = RI.ID_CATEGORIE', array());
		return $this->getDbTable()->fetchPairs($oSelect);
	}
	

}