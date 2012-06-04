<?php

class Form_AjoutItem extends Projet_Form {
	
	const LIBELLE = 'Libelle';
	const DESCRIPTION	= 'Description';
	const CATEGORIE		= 'Categorie';
	
	public function __construct(array $aCategories = array()) {
		parent::__construct('AjoutLibelle');
		$this->createForm($aCategories);
		$this->setSrvMethodes('Application_Service_Items', 'formSave');
	}
	
	public function createForm(array $aCategories) {
		
		$oLibelle = new Projet_Form_Element_Text(self::LIBELLE);
		$oLibelle->setLabel('Libelle')->setRequired(true);
		
		$oDescription = new Projet_Form_Element_TextArea(self::DESCRIPTION);
		$oDescription->setLabel('Description');
		
		$oCategorie = new Projet_Form_Element_Select(self::CATEGORIE);
		$oCategorie->setLabel('Categorie')
				   ->setMultiOptions($aCategories);
		
		$this->addElementsWithSubmit(array($oCategorie, $oLibelle, $oDescription), 'Enregistrer');
		$this->setAttrib('class', CSS_FORM_MEDIUM);
		$this->setDefaultDecorators();
		
		
	}
	
	
}