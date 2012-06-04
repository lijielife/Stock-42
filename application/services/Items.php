<?php
class Application_Service_Items {
	
	public function formSave(array $aResults = array()) {
		$oMapper = new Application_Model_Mapper_Libelles();
		$oLibelle = new Application_Model_Libelle();
		$oLibelle->setDescription($aResults[Form_AjoutItem::DESCRIPTION]);
		$oLibelle->setLibelle($aResults[Form_AjoutItem::LIBELLE]);
		$oLibelle->setIdCategorie($aResults[Form_AjoutItem::CATEGORIE]);
		$oMapper->save($oLibelle);
	}
	
}