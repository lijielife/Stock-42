<?php

class Form_AjoutProduit extends Projet_Form {
	
	const CATEGORIE = 'Categorie';
	const LIBELLE	= 'Libelle';
	const QUANTITE	= 'Quantite';
	const DATE_PER	= 'Date_Per';
	const DUREE_PER	= 'Duree_Per';
	
	public function __construct($aCategoriesProduits = array()) {
		parent::__construct('AjoutProduit');
		$this->createForm($aCategoriesProduits);
		$this->setSrvMethodes('Application_Service_Produit', 'formAjoutSave');
	}
	
	public function createForm($aCategoriesProduits) {
		
		$oCategorie = new Projet_Form_Element_Select(self::CATEGORIE);
		$oCategorie->setMultiOptions($aCategoriesProduits)->setLabel('Categorie');
		
		
		$oItem = new Projet_Form_Element_Select(self::LIBELLE, array('ajax' => 'span'));
		$oItem->setLabel('Item');
		
		$oQuantite = new Projet_Form_Element_Text(self::QUANTITE);
		$oQuantite->setLabel('Quantité');
		
		$oDatePeremption = new Projet_Form_Element_DatePicker(self::DATE_PER);
		$oDatePeremption->setLabel('Date de péremption');
		
		$oDureePeremption = new Projet_Form_Element_Text(self::DUREE_PER);
		$oDureePeremption->setLabel('Durée de péremption');
		
		$this->addElementsWithSubmit(array($oCategorie, $oItem, $oQuantite, $oDatePeremption, $oDureePeremption), 'Ajouter le produit');
		$this->setAttrib('class', CSS_FORM_MEDIUM);
		$this->setDefaultDecorators();
		
	}
	
	
}