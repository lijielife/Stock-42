<?php

class Application_Model_Mapper_RefLogins extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_RefLogins');
	}
	
	/**
	 * 
	 * @param int nId contien l'id de l'utilisateur
	 */
	public function updateLogin($nId) {
		$this->getDbTable()->insert(array('ID_USERS' => (int) $nId));		
	}
	
	public function getLoginsDates($nIdUser) {
		$oSelect = $this->getDbTable()->select()->from($this->getDbTable()->getTableName(), array('LOG_DATE'))->where('ID_USERS = ?',$nIdUser)->order('ID');
		return $this->getDbTable()->fetchAll($oSelect);
	}

}