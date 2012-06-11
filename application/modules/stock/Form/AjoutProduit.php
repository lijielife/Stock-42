<?php

class Form_AjoutProduit extends Projet_Form {
	
	const CATEGORIE = 'Categorie';
	const LIBELLE	= 'Libelle';
	const QUANTITE	= 'Quantite';
	const DATE_PER	= 'Date_Per';
	const DUREE_PER	= 'Duree_Per';
	const DATE_DUREE = 'Date_Duree';
	
	const N_DATE			= 1;
	const N_DUREE			= 2;
	const N_NONPERISSABLE	= 3;
	
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
		
		$oDec = new Zend_Form_Decorator_HtmlTag(array('tag'	=> 'span',
				  								  'id'	=> 'unite',
				  								  'placement' => 'append'));
		
		$oQuantite = new Projet_Form_Element_Text(self::QUANTITE, array('deco' => $oDec));
		$oQuantite->setLabel('Quantité')
				  ->setRequired(true)
				  ->setAllowEmpty(false)
				  ->addValidator('Float', true)
				  ->addValidator('GreaterThan', true, array('min' => 0));
// 				  ->addDecorator('HtmlTag', array('tag'	=> 'span',
// 				  								  'id'	=> 'unite',
// 				  								  'placement' => 'append'));
		
		$oSelectDateDuree = new Projet_Form_Element_Radio(self::DATE_DUREE);
		$oSelectDateDuree->setMultiOptions(array( self::N_DATE 			=> 'Date',
												  self::N_DUREE			=> 'Durée'))
						 ->setSeparator("&nbsp \t")
						 ->setRequired(true);
		
		
		$oDatePeremption = new Projet_Form_Element_DatePicker(self::DATE_PER);
		$oDatePeremption->setLabel('Date de péremption');
		
		$oDureePeremption = new Projet_Form_Element_Text(self::DUREE_PER);
		$oDureePeremption->setLabel('Durée de péremption')
						 ->addValidator('Float', true)
						 ->addValidator('GreaterThan', true, array('min' => 0));
		
		$this->addElementsWithSubmit(array($oCategorie, $oItem, $oQuantite, $oSelectDateDuree, $oDatePeremption, $oDureePeremption), 'Ajouter le produit');
		$this->setAttrib('class', CSS_FORM_MEDIUM);
		$this->setDefaultDecorators();
		
	}
	
	
}