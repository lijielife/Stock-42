<?php

/** @brief	element de formulaire trigramme
 *
 * Element de formulaire trigramme
 * ---- à utiliser lorsque l'élement à rentrer est un trigramme quelconque ----
 * @deprecated
 * @author	francoisespinet
 */
class Projet_Form_Element_Trigramme extends Zend_Form_Element_Text {
	
	protected $id = "TRIGRAMME";
	
	/**
	 * @brief	Constructeur de classe
	 *
	 *
	 * @author	francoisespinet
	 * @version 1 mars 2012 - 11:58:44
	 */
	function __construct() {
		//appel du constructeur du parent
		parent::__construct($this->id);
		//ajout du validateur
		$this->addValidator(new Projet_Validate_Trigramme());
	}

}
