<?php

/**
 * Decorateur permettant de créer un paragraphe
 * @author	francoisespinet
 */
class Projet_Form_Decorator_Paragraphe extends Zend_Form_Decorator_Abstract {
	
	const PREFIX		= 'PREFIX';
	const SUFFIX		= 'SUFFIX';
	/*
	 * Le contenu de ces variables sera inséré respectivement avant et aprés le nom du champ pour constituer l'id du paragraphe
	 */
	protected $_prefix = 'champ-';
	protected $_suffix = '';
	
	public function render($sContent) {
		$this->getOption(self::PREFIX) && $this->_prefix =  $this->getOption(self::PREFIX);
		$this->getOption(self::SUFFIX) && $this->_suffix = $this->getOption(self::SUFFIX);
		$oElement = $this->getElement();
		//création du symbol du paragraphe
		$oChamp = new Projet_Xml('p');
		//ajout de l'attribut pour l'alignement (cf template)
// 		$oChamp->setAttribute('class', $sClasses);
		$oChamp->setAttribute('id', $this->_prefix.$oElement->getName().$this->_suffix);
		$oChamp->setAttr('class', CSS_FORM_CHAMP);
		$oChamp->setData($sContent);
		return $oChamp->render();
	}
	
		
}
