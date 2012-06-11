<?php

class Form_AjoutItem extends Projet_Form {
	
	const LIBELLE = 'Libelle';
	const DESCRIPTION	= 'Description';
	const CATEGORIE		= 'Categorie';
	const CONSERVATION	= 'Conservation';
	const MESURE		= 'Mesure';
	
	public function __construct(array $aCategories = array(), $aConservation = array(), $aUnites = array()) {
		parent::__construct('AjoutLibelle');
		$this->createForm($aCategories, $aConservation, $aUnites);
		$this->setSrvMethodes('Application_Service_Items', 'formSave');
	}
	
	public function createForm(array $aCategories, array $aConservation, array $aUnites) {
		
		$oLibelle = new Projet_Form_Element_Text(self::LIBELLE);
		$oLibelle->setLabel('Libelle')->setRequired(true);
		
		$oDescription = new Projet_Form_Element_TextArea(self::DESCRIPTION);
		$oDescription->setLabel('Description');
		
		$oCategorie = new Projet_Form_Element_Select(self::CATEGORIE);
		$oCategorie->setLabel('Categorie')
				   ->setMultiOptions($aCategories);
		
		$oTypeConservation = new Projet_Form_Element_MultiCheckBox(self::CONSERVATION);
		$oTypeConservation->setLabel('Conservation')
						  ->setMultiOptions($aConservation);
		
		$oMesure = new Projet_Form_Element_Select(self::MESURE);
		$oMesure->setLabel('Unité de mesure')
				->setMultiOptions($aUnites);
		
		$this->addElementsWithSubmit(array($oCategorie, $oLibelle, $oTypeConservation, $oMesure, $oDescription), 'Enregistrer');
		$this->setAttrib('class', CSS_FORM_MEDIUM);
		$this->setDefaultDecorators();
		
		
	}
	
	
}