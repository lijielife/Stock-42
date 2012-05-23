<?php

/**
 * Décoration brute des Elements MultiInput
 * Il s'agit des élément qui comporte plusieurs champs input (aux nombre parmétrable par exemple)
 * @author	francoisespinet
 */
class Projet_Form_Decorator_MultiInput extends Projet_Form_Decorator_Abstract {
	
	//tableau des adresses invalides
	protected $_aInvalidAdresses = array();
	
	protected function render_element() {
		//récuperation des attributs utiles
		$oElement = $this->getElement();
		$aValues = $oElement->getValue();
		$sName = $oElement->getName();
		$sTitre = $oElement->getLabel();
		//récuperation des id des adresses invalides
		$this->_aInvalidAdresses = $oElement->getInvalidChamps();
		
		//création de la div entourante
		$oDiv = new Symbol_Div('MultiInput');
		$oDiv->setAttribute('id', $sName.'-element');
		//création du label
		$oLabel = new Symbol_Label($sTitre);
		$oLabel->setAttribute('for', $sName);
		//attachement du label dans la div
		$oDiv->linkSymbol($oLabel);
		//s'il y à plusieurs champs
		if ($aValues) {
			//on extrait la première adresse au du traitement générique
			$sFirstAdresse = array_shift($aValues);
			//on construit l'input et on l'ajout
			$oInput = new Symbol_Input($sName.'[]', 'text', $sFirstAdresse);
			$oInput->setAttribute('id', $sName);
			//on ajoute les erreurs s'il y en a
			$oInput = $this->addErrorDeco($oInput, 0);
			$oDiv->linkSymbol($oInput);
			
			$i=0;
			//on parcours les autres champs et on les construit comme précedement
			foreach ($aValues as $sAdresse) {
				$i++;
				$oInput2 = new Symbol_Input($sName.'[]', 'text', $sAdresse);
				$oInput2->setAttribute('id', $sName.'-'.$i);
				$oInput2 = $this->addErrorDeco($oInput2, $i);
				$oDiv->linkSymbol($oInput2);
			}
		} else {
			//s'il n'y avait aucun champ, on construit un champ vide
			$oInput = new Symbol_Input($sName.'[]', 'text');
			$oInput->setAttribute('id', $sName);
			$oInput = $this->addErrorDeco($oInput, 0);
			$oDiv->linkSymbol($oInput);
		}
		return $oDiv->render();
	}
	
	/**
	 * @brief	Ajout des erreurs sur les champs incriminés
	 *
	 * @author		francoisespinet
	 * @version		13 avr. 2012 - 12:04:19
	 *
	 * @param 	Symbol_Input $oSymbol le symbol input comme construit
	 * @param 	int $nId l'id du champ incriminé
	 * @return	return_type
	 */
	protected function addErrorDeco(Symbol_Input $oSymbol, $nId = null) {
		//si il y a des erreurs sur cet ensemble de champs
		if ($this->_aInvalidAdresses) {
			//on sauvegarde l'ancien input
			$oInput = $oSymbol;
			//on réécrit le nouveau avec une décoration classique des erreurs
			$oSymbol = new Symbol_Span('', CSS_POS_RELATIVE);
			//s'il y a une erreur sur ce champ en particulier
			if ($nId !== null && in_array($nId, $this->_aInvalidAdresses)) {
				$oSpan = new Symbol_Span('', CSS_INPUT_CHECK_ERROR);
				$oInput->addAttribute('class', CSS_INPUT_ERROR);
			} else {
				//sinon on met ok
				$oSpan = new Symbol_Span('', CSS_INPUT_CHECK_OK);
			}
			//on retourne le symbol décoré
			return $oSymbol->linkSymbols(array($oInput, $oSpan));
		} else {
			//on renvoie le symbole identique s'il n'y avait pas d'erreur
			return $oSymbol;
		}
	}
	
}
