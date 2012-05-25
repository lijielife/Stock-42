<?php

class Form_AjoutProduit extends Projet_Form {
	
	public function __construct($aCategoriesProduits = array()) {
		parent::__construct('AjoutProduit');
		$this->createForm($aCategoriesProduits);
	}
	
	public function createForm($aCategoriesProduits) {
		
		$oCategorie = new Projet_Form_Element_Select('Categorie');
		$oCategorie->setMultiOptions($aCategoriesProduits)->setLabel('Categorie');
		
		
		$oItem = new Projet_Form_Element_Select('Item');
		$oItem->setAttrib('ajax', true)->setLabel('Item');
		
		$oQuantite = new Projet_Form_Element_Text('Quantite');
		$oQuantite->setLabel('Quantité');
		
		
		$this->addElementsWithSubmit(array($oCategorie, $oItem, $oQuantite), 'Enregistrer');
		$this->setAttrib('class', CSS_FORM_SMALL);
		$this->setDefaultDecorators();
		
	}
	
	
}