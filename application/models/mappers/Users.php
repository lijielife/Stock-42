<?php

class Application_Model_Mapper_Users extends Projet_Mapper {
	
	
	public function __construct() {
		parent::__construct('Application_Model_DbTable_Users');
	}
	
	public function updateLastLogin($nId) {
		$where['ID = ?'] = $nId;
		$this->getDbTable()->update(array('ID' => $nId, 'DERNIERE_CONNEXION' => time()), $where);
	}
	
	public function getLastLoginDate($nId) {
		$oSelect = $this->getDbTable()->select()->from($this->getDbTable()->getTableName(), array('DERNIERE_CONNEXION'))->where('ID = ?', (int) $nId);
		return $this->getDbTable()->fetchOne($oSelect);
	}
}