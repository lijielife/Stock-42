<?php

class Application_Model_Mapper_RefMesures extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_RefMesures');
	}
	
	public function getLibellesReels() {
		$oSelect = $this->getDbTable()->select()->setIntegrityCheck(false)
												->from(array('RM' => 'REF_MESURES'), array('RC.ID', 'RC.LIBELLE'))
												->join(array('RI' => 'REF_ITEMS'), 'RI.ID_MESURE = RM.ID', array());
		return $this->getDbTable()->fetchPairs($oSelect);
	}
	
	
	public function getUniteByIdItem($nIdItem) {
		$oDb = $this->getDbTable();
		$oSelect = $oDb->select()->from(array('RM' => 'REF_MESURES'), array( 'RM.UNITE'))
								 ->join(array('RI' => 'REF_ITEMS'), 'RI.ID_MESURE = RM.ID', array())
								 ->where('RI.ID = ?', $nIdItem);
		return $oDb->fetchOne($oSelect);
	}
	

}