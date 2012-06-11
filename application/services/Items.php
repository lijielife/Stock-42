<?php
class Application_Service_Items {
	
	public function formSave(array $aResults = array()) {
		$oMapper = new Application_Model_Mapper_RefItems();
		$oLibelle = new Application_Model_Item();
		$oLibelle->setDescription($aResults[Form_AjoutItem::DESCRIPTION]);
		$oLibelle->setLibelle($aResults[Form_AjoutItem::LIBELLE]);
		$oLibelle->setIdCategorie($aResults[Form_AjoutItem::CATEGORIE]);
		if ($aResults[Form_AjoutItem::MESURE]) { 
			$oLibelle->setIdMesure($aResults[Form_AjoutItem::MESURE]);
		}
		$oMapper->save($oLibelle);
	}
	
}