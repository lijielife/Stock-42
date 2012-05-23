<?php
/**
 *
 * Décorateur specifique
 * Attention :
 * ne gere que l'input et le submit!!
 * Construit les input en chaine
 * @author	francoisespinet
 *
 */
class Projet_Form_Decorator_JoinedInput extends Projet_Form_Decorator_Abstract {

	protected $aoForm = null;
	
	/**
	 * Décorateur spécifique au formulaire ne comportant que des input devant etre aligné.
	 * Par exemple dans:
	 * @see Form_TypeAppareil
	 * @author francois.espinet
	 * @see Projet_Form_Decorator_Abstract::render_element()
	 */
	protected function render_element() {

		$oForm = new Symbol_Void();//objet formulaire
		$this->aoForm = $this->getElement();//on récupere les éléments
		//parcours des éléments du formulaire (les champs)
		foreach ($this->aoForm as $oItem) {
			/*
			 * Symbole pour gérer l'ensemble du champ
			 * Eventuellement certains éléments peuvent être simplement juxtaposés sans être contenus dans un autre symbole
			 * D'où le symbole void par défault
			 */
			$oChamp = new Symbol_Void();
			$bChampHasError = $oItem->hasErrors();
			//on ecarte submit pour les erreurs
			if ($oItem instanceof Zend_Form_Element_Text) {
				
				if($bChampHasError) {
					// on ajoute une classe pour la décoration contour rouge
					$oItem->setAttrib('class', CSS_INPUT_ERROR.$oItem->getAttrib('class'));
				}
				$oChamp = new Symbol_Span('','JoinedInput');
				//on genere les données
				$sChamp = $this->buildLabelAndInput($oItem);
			
				$oChamp->setData($sChamp);

			} elseif ($oItem instanceof Zend_Form_Element_Submit) {
				$oChamp = new Symbol_Void($this->buildSubmit($oItem));
			} elseif ($oItem instanceof Zend_Form_Element_Hidden ) {
				$oChamp = new Symbol_Void($this->buildInput($oItem));
			}
			//on attache l'élement au formulaire
			$oForm->linkSymbol($oChamp);
		}
		
		return $oForm->render();//on renvoie la chaine
	}
	
}