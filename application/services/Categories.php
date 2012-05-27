<?php
class Application_Service_Categories {
	
	public function formSave(array $aResults = array()) {
		$oMapper = new Application_Model_Mapper_Categories();
		$oCategorie = new Application_Model_Categorie();
		$oCategorie->setDescription($aResults[Form_AjoutCategorie::DESCRIPTION]);
		$oCategorie->setLibelle($aResults[Form_AjoutCategorie::CATEGORIE]);
		$oMapper->save($oCategorie);
	}
	
}