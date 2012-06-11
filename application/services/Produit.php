<?php
class Application_Service_Produit {
	
	public function listerCols() {
		return array('Date de Péremption', "Date d'achat", 'Libellé', 'Catégorie', 'Quantité restante');
	}
	
	public function listerTousTous() {
		$oMapper = new Application_Model_Mapper_Produits();
		return $oMapper->listerToutUtilistateurs();
	}
	
	public function listerFraisTous() {
		$oMapper = new Application_Model_Mapper_Produits();
		return ($oMapper->listerFraisToutUtilisateurs() ? :array());
	}
	
	public function listerNonFraisTous() {
		$oMapper = new Application_Model_Mapper_Produits();
		return ($oMapper->listerNonFraisToutUtilisateurs() ? :array());
	}
	
	
	public function formAjoutSave($aResults) {
		$oEntite = new Application_Model_Produit();
		$oEntite->setIdLibelle($aResults[Form_AjoutProduit::LIBELLE]);
		$oEntite->setQuantite($aResults[Form_AjoutProduit::QUANTITE]);
		if ($aResults[Form_AjoutProduit::DATE_DUREE] == Form_AjoutProduit::N_NONPERISSABLE) {
			//TODO:eventuellement faire quelque chose ici!
		} elseif ( $aResults[Form_AjoutProduit::DATE_DUREE] == Form_AjoutProduit::N_DUREE) {
			$oEntite->setPer( date(PDO_DATE_FORMAT, mktime(0, 0, 0, date("m"), date("d")+$aResults[Form_AjoutProduit::DUREE_PER],  date("Y"))));
		} else {
			$oEntite->setPer($aResults[Form_AjoutProduit::DATE_PER]);
		}
		$oMapper = new Application_Model_Mapper_Produits();
		$oMapper->save($oEntite);
	}
}