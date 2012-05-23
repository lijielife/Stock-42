<?php

/**
 * classe abstraite pour les décorateurs
 *
 *
 * @author	francoisespinet
 *
 */
abstract class Projet_Form_Decorator_Abstract extends Zend_Form_Decorator_Abstract {

	const FORM_HAS_ERROR = 'FormHasError';
	
	abstract protected function render_element();
	
	/**
	 * Render form elements
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content) {
		$separator      = $this->getSeparator();
		$elementContent = $this->render_element();

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $elementContent . $separator . $content;
			case self::APPEND:
			default:
				return $content . $separator . $elementContent;
		}
	}
	
	
	/**
	 * Construit le bouton submit avec l'aide du helper de vue de Zend
	 * @brief	construit et décore un bouton submit
	 * @author	francoisespinet
	 * 	@param Zend_Form_Element_Submit $oElement
	 */
	protected function buildSubmit(Zend_Form_Element_Submit $oElement) {
		
		//on ajoute la classe bouton au classes déjaprésentes
		$sClass = $oElement->getAttrib('class');
		$oElement->setAttrib('class',Projet_DataHelper::concatMots($sClass, trim(CSS_BUTTON)));

//		$oButton = new Symbol_Button('', $oElement->getValue());
//		$oButton->setData($oElement->renderViewHelper());
//		return $oButton->render();
		return $oElement->renderViewHelper();
	}
	
	/**
	 * @brief	Construit le label et l'input et le concatene
	 *
	 * Methode Proxy
	 * @see $this->buildInput
	 * @see $this->buildLabel
	 *
	 * @author		francoisespinet
	 * @version		9 mars 2012 - 10:23:23
	 * @param Zend_Form_Element $oElement
	 */
	protected function buildLabelAndInput(Zend_Form_Element $oElement) {
		return $this->buildLabel($oElement).$this->buildInput($oElement);
	}
	/**
	 * Construit un champ de type input (générique) avec le helper de Zend correspondant a ce champ
	 *
	 * @brief	construit un champ générique
	 * @author	francoisespinet
	 * 	@param Zend_Form_Element $oElement
	 */
	protected function buildInput(Zend_Form_Element $oElement, $bNoErreurs = false) {
		//ajout des erreurs si besoin
		if ($oElement->getAttrib(self::FORM_HAS_ERROR) && !$bNoErreurs) {
			return $this->formAddErreurChamp($oElement->renderViewHelper(), $oElement->hasErrors());
		} else {
			return $oElement->renderViewHelper();
		}
	}
	
	/**
	 * Construit le Label du champ
	 * Ajoute la classe required si le champ est marque comme requis
	 * @brief	Construit le label
	 * @author	francoisespinet
	 * 	@param Zend_Form_Element $oElement
	 */
	protected function buildLabel(Zend_Form_Element $oElement) {
		// On passe par l'instanciantion à la différence de buildInput
		// car le décorateur label chargé par défaut wrap un dt autour du label
		$oDecorator = new Zend_Form_Decorator_Label();
		return $oDecorator->setElement($oElement)->render('');
	}
	
	
	/**
	 * @brief	Ajout la decoration du champ s'il à une erreur ou si un de ses camarades en à une
	 *
	 * @see Projet_Form_Decorator_Generic
	 *
	 * @author		francoisespinet
	 * @version		9 mars 2012 - 11:14:41
	 * @param unknown_type $sChamp
	 * @param Html_Symbol $oChamp
	 * @param Zend_Form_Element $oItem
	 * @param bool $bChampHasError
	 */
	protected function formAddErreurChamp($sChamp, $bChampHasError) {
		$oChampVoid = new Symbol_Void();
		//Span englobante pour la position de la croix
		$oSpanErrorOk = new Symbol_Span('',CSS_POS_RELATIVE);
		//on ajoute le contenu du champ
		$oSpanErrorOk->linkSymbol(new Symbol_Void($sChamp));
		$sClass = '';
		/*
		 * Deux comportements sont possibles :
		 * 	-> il y un erreur su ce champ, auquel cas on décore avec un croix rouge et un input rouge
		 * 	-> il n'y à pas d'erreur sur ce champ mais sur un autre champ, on décore alors avec une check bleue
		 */
		if ($bChampHasError) {
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
		return $oChampVoid->render();
	}
	
}
