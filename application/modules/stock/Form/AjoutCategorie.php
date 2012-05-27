<?php

class Form_AjoutCategorie extends Projet_Form {
	
	const CATEGORIE = 'Categorie';
	const DESCRIPTION	= 'Description';
	
	public function __construct() {
		parent::__construct('AjoutCategorie');
		$this->createForm();
		$this->setSrvMethodes('Application_Service_Categories', 'formSave');
	}
	
	public function createForm() {
		
		$oCategorie = new Projet_Form_Element_Text(self::CATEGORIE);
		$oCategorie->setLabel('Categorie')->setRequired(true);
		
		
		$oDescription = new Projet_Form_Element_TextArea(self::DESCRIPTION);
		$oDescription->setLabel('Description');
		
		$this->addElementsWithSubmit(array($oCategorie, $oDescription), 'Enregistrer');
		$this->setAttrib('class', CSS_FORM_MEDIUM);
		$this->setDefaultDecorators();
		
		
	}
	
	
}