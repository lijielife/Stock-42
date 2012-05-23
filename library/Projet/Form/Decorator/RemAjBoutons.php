<?php

/**
 * Décoration brute des Elements RemAjBoutons
 * Attention, correspone en fait à 2 boutons
 * @author	francoisespinet
 */
class Projet_Form_Decorator_RemAjBoutons extends Projet_Form_Decorator_Abstract {
	
	//préfixes des boutons ajouter et supprimer
	const AJ		= 'add';
	const RM		= 'rem';
	const BUT		= 'Bouton';
	//label des boutons
	const AJ_LAB	= '+';
	const RM_LAB	= '-';
	
	//la vue pour le helper
	protected $oView = null;
	
	protected function render_element() {
		//recuperation de l'élément et de ses attributs
		$oElement = $this->getElement();
		$sName = $oElement->getName();
		$this->oView = $oElement->getView();
		//on place les deux boutons dans un span
		$oSpan = new Symbol_Span('', 'RemAjBouton');
		$oSpan->setAttribute('id', $sName.'-Boutons');
		$oSpan->setAttribute('style', 'display:inline-block;');
		//on ajoute les boutons ajouter et supprimer
		$oSpan->addData($this->createBouton(self::AJ, $sName));
		$oSpan->addData($this->createBouton(self::RM, $sName));
		//on retourne l'ensemble
		return $oSpan->render().'<br/>';
	}
	
	/**
	 * @brief	Routine de création du bouton
	 *
	 * @author		francoisespinet
	 * @version		13 avr. 2012 - 12:33:51
	 *
	 * @param 	string $sType type du bouton (ajouter ou supprimer)
	 * @param 	string $sName le nom du bouton
	 * @return	string Le bouton généré
	 */
	protected function createBouton($sType, $sName) {
		
		if ($sType == self::AJ) {
			$sLabel = self::AJ_LAB;
		} elseif ($sType == self::RM) {
			$sLabel = self::RM_LAB;
		}
		//utilisation du helper de zend pour construire les boutons
		//on renvoie le bouton
		return $this->oView->formButton($sName, $sLabel, array('id' 		=> $sType.$sName,
																			 'class'	=> $sType.self::BUT));
	}
	
}