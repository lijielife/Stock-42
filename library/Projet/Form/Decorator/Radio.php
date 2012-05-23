<?php

/**
 * Décoration SIMPLE des éléments radio (pratique en ajax)
 *
 * @author	francoisespinet
 *
 */
class Projet_Form_Decorator_Radio extends Zend_Form_Decorator_Abstract {

	public function render($sContent) {
		
		$oElement = $this->getElement();
		//on recupere le nom de l'élement défini dans le formulaire
		$sName = $oElement->getName();
		
		//création d'une liste
		$oUl = new Symbol_List();
		//ajout des attribut template
		$oUl->setAttribute('class', CSS_INPUT_RADIO);
		$oUl->setAttribute('id', 'liste-'.$oElement->getName());
		
		//récupération de la valeur à cocher (définie dans le setValue)
		$nValueActive = $oElement->getValue();
		// on parcours chaque élement de multioptions
		foreach ($oElement->getMultiOptions() as $iValue=>$sOption) {
			//on créé un item de liste
			$oLi = new Symbol_ListItem();
			//on construit l'input
			$oInput = new Symbol_Input($sName, 'radio', $iValue);
			//on l'active si besoin
			if ((string) $iValue == $nValueActive && $nValueActive !== NULL) {
				$oInput->addAttribute('checked', 'checked');
			}
			$oInput->addAttribute('id', $sName."-".$iValue);
			//on construit le label
			$oLabel = new Symbol_Label($sOption, $oInput);
			//on ajout le tout au symbole liste
			$oLi->linkSymbols(array($oInput, $oLabel));
			//on ajoute l'élément à la liste
			$oUl->linkSymbol($oLi);

			/*
			 * On ajoute un élement div au niveau de l'endroit ou est indique Disponibilité restreinte
			 * si c'est spécifié dans l'option dispo_restreinte
			 */
			if ($iValue == ETAT_DISPO_RESTREINTE && $this->getOption('dispo_restreinte')) {
				$oLiBis = new Symbol_ListItem();
				//création du symbole div
				$oDiv = new Symbol_Div();
				$oDiv->addAttribute('id', 'result-'.$sName);
				$oDiv->addAttribute('class', 'result');
				$oLiBis->linkSymbol($oDiv);
				$oUl->linkSymbol($oLiBis);
			}
		}
		// on rends tout
		$sElementContent = $oUl->render();
		//on permet le chainage avec les options placement
		$separator = $this->getSeparator();

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $sElementContent . $separator . $sContent;
			case self::APPEND:
				return $sContent . $separator . $sElementContent;
			default:
				return $sContent . $separator . $sElementContent;
		}
	}
}
