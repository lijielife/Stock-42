<?php

class Layout_Helper_LoginZone extends Zend_View_Helper_Abstract {
	
	public function loginZone($oIdent = null) {
		if ($oIdent === null ) {
			return "aucune indentite";
		}
		$oIdent = (array) $oIdent;
		return $oIdent['NOM']." ".$oIdent['PRENOM'];
	}
	
	
}