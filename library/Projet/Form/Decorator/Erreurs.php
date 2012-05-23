<?php

/**
 * Decoration du champ (span et croix ou coche ok)
 *
 * @author	francoisespinet
 * @param
 */
class Projet_Form_Decorator_Erreurs extends Zend_Form_Decorator_Abstract {
	
	public function render($sContent) {
		//permet de savoir s'il y a une erreur sur le formulaire en globalité
		$bFormHasErreur =$this->getElement()->getView()->errors;
		//permet de savoir si le champ traité à une erreur ou pas
		$bChampHasErreur = $this->getElement()->hasErrors();
		/*
		 * Si le formulaire comporte une erreur, il faut alors décorer tous les champs avec :
		 * - soit une coche ok si le champ traité n'a pas d'erreur
		 * - soit une coche croix si le champ a bien une erreur
		 */
		if ($bFormHasErreur) {
			$oChampVoid = new Symbol_Void();
			//Span englobante pour la position de la croix
			$oSpanErrorOk = new Symbol_Span('',CSS_POS_RELATIVE);
			//on ajoute le contenu du champ
			$oSpanErrorOk->linkSymbol(new Symbol_Void($sContent));
			/*
			 * Deux comportements sont possibles :
			 * 	-> il y un erreur su ce champ, auquel cas on décore avec un croix rouge et un input rouge
			 * 	-> il n'y à pas d'erreur sur ce champ mais sur un autre champ, on décore alors avec une check bleue
			 */
			if ($bChampHasErreur) {
				//Classe pour la croix
				$sClass = CSS_INPUT_CHECK_ERROR;
				//on ajoute les erreurs (le texte des erreurs)
			} else {
				//Classe pour le coche
				$sClass = CSS_INPUT_CHECK_OK;
			}
			//ajout de la croix ou de la coche, c'est selon
			$oSpanErrorOk->linkSymbol(new Symbol_Span('',trim($sClass)));
			$oChampVoid->linkSymbol($oSpanErrorOk);
			//on retourne le champ
			return $oChampVoid->render();
		} else {
			//si pas d'erreur, on touche à rien
			return $sContent;
		}
		
	}
	
		
}
