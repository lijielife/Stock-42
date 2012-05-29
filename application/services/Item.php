<?php
class Application_Service_Item {
	
	public function listerCols() {
		return array('Date de Peremption', "Date d'achat", 'Libellé', 'Catégorie','Quantité restante', 'ID');
	}
	public function listerTousItem() {
		$oMapper = new Application_Model_Mapper_Item();
		return $oMapper->listerToutUtilistateurs();
	}
	
}