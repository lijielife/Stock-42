<?php

/**
 * Décorateur pour les select et les input text
 *
 * @deprecated
 * @author	francoisespinet
 *
 */
class Projet_Form_Decorator_SelectAndText extends Projet_Form_Decorator_Abstract {
	
	//le tag à mettre eventuellement autour de l'élement lui même (pour remplacement en ajax)
	protected $sTag = null;
	
	public function __construct($sTag = null) {
		parent::__construct();
		$this->sTag = $sTag;
	}
	
	protected function render_element() {
		$oElement = $this->getElement();
		//Le conteneur pour ces champs sera le Paragraphe : on ecrase le précedent
		$oChamp = new Symbol_P();
		//ajout de l'attribut pour l'alignement (cf template)
		$oChamp->setAttribute('class', CSS_LABEL_INLINE_PADDING_SMALL);
		$oChamp->setAttribute('id', 'champ-'.$oElement->getName());
		//si l'élément est destiné à être utilisé en ajax alors on l'entoure d'un span
		if ($this->sTag !== null) {
			$oSpan = new Projet_Xml($this->sTag);
			$oSpan->setAttr('id', $oElement->getName().$this->sTag);
			$oSpan->append($this->buildInput($oElement));
			$sChamp=$this->buildLabel($oElement).$oSpan->render();
		} else {
			//on genere les données avec les erreurs éventuelles 'concernants le champ
			$sChamp = $this->buildLabelAndInput($oElement);
		}
		$oChamp->setData($sChamp);
		$oElement->removeDecorator('ViewHelper');
		return $oChamp->render();
	}
	
	
}
