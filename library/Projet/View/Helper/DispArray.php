<?php

class Projet_View_Helper_DispArray extends Zend_View_Helper_Abstract {
	
	private $_TRANSLATE = array('PRENOM'	=> 'Prénom',
							  'NOM'			=> 'Nom',
							  'DERNIERE_CONNEXION'	=> 'Date de la dernière connexion',
							  'LOGIN'				=> "Nom d'utilisateur"
							  );
	private $_IGNORE	  = array('ID', 'MOE', 'PER');

	public function dispArray(array $aArray = null) {
		$oSpan = new Projet_Xml('span');
		$oSpan->setAttr('style', 'position:relative;');
		$oTable = new Projet_Xml('table');
		foreach ($aArray as $sCle => $sValue) {
			if (!in_array($sCle, $this->_IGNORE)) {
				$this->addElements($this->addRow($oTable), array($this->_TRANSLATE[$sCle], $sValue));
			}
		}
		$oSpan->append($oTable);
		return $oSpan->render();
	}
	
	protected function addElement(Projet_Xml $oRow, $sContent) {
		$oRow->append(new Projet_Xml('td', array(), $sContent));
	}
	
	protected function addElements(Projet_Xml $oRow, $aContent) {
			foreach ($aContent as $sContent) {
				$this->addElement($oRow, $sContent);
			}
	}
	
	protected function addRow(Projet_Xml $oTable) {
		$oRow = new Projet_Xml('tr');
		$oTable->append($oRow);
		return $oRow;
	}
	
	
	
}