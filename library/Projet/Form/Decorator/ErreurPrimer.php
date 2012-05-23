<?php

/**
 * Decorateur de préparation des erreurs : permet le rendu du liseret rouge en cas d'erreur
 *
 * @author	francoisespinet
 */
class Projet_Form_Decorator_ErreurPrimer extends Zend_Form_Decorator_Abstract {
	
	public function render($sContent) {
		//ajout de l'attribut class error si besoin sur le champ
		if ($this->getElement()->hasErrors()) {
			$this->getElement()->setAttrib('class', CSS_INPUT_ERROR);
		}
		return $sContent;
	}
	
		
}
