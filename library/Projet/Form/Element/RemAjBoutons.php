<?php

/** @brief	element de formulaire bouton ajouter et supprimer
 *
 * Cet élément correspond en fait à deux boutons : + et -
 * @author	francoisespinet
 */
class Projet_Form_Element_RemAjBoutons extends Zend_Form_Element_Button {
	
	
	/**
	 * @brief	Constructeur de classe
	 *
	 *
	 * @author	francoisespinet
	 * @version 1 mars 2012 - 11:58:44
	 */
	function __construct($sId) {
		//appel du constructeur du parent
		parent::__construct($sId);
		//utilisation de notre dé notre décorateur joli
		$this->setDecorators(array(new Projet_Form_Decorator_RemAjBoutons()));
	}

}
