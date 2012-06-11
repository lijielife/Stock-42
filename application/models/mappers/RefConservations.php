<?php

class Application_Model_Mapper_RefConservations extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_RefConservations');
	}
	
	public function getLibellesReels() {
		$oSelect = $this->getDbTable()->select()->setIntegrityCheck(false)
												->from(array('RC' => 'REF_CONSERVATIONS'), array('RC.ID', 'RC.LIBELLE'))
												->join(array('LIC' => 'L_ITEMS_CONSERVATIONS'), 'RC.ID = LIC.ID_CONSERVATION', array());
		return $this->getDbTable()->fetchPairs($oSelect);
	}
	

}