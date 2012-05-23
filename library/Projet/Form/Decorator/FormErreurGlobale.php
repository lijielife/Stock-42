<?php

/**
 * Decorateur permettant de créer un paragraphe
 * @author	francoisespinet
 */
class Projet_Form_Decorator_FormErreurGlobale extends Zend_Form_Decorator_Abstract {
	
	const PREFIX		= 'PREFIX';
	const SUFFIX		= 'SUFFIX';
	/*
	 * Le contenu de ces variables sera inséré respectivement avant et aprés le nom du champ pour constituer l'id du paragraphe
	 */
	protected $_prefix = 'champ-';
	protected $_suffix = '';
	
	public function render($sContent) {
		if ($this->getElement()->isErrors()) {
			$oDivErreur = new Projet_Xml('div');
			$oDivErreur->setAttr('class', CSS_FORM_ERREUR);
			foreach ($this->getElement()->getErrorMessages() as $message) {
				$oPErreur = new Projet_Xml('p');
				$oPErreur->append($message);
				$oDivErreur->append($oPErreur);
			}
			return $oDivErreur->render().$sContent;
		} else {
			return $sContent;
		}
	}
	
		
}
