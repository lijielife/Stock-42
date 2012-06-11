<?php

/**
 * Décoration brute des Elements multicheck box
 * Le comportement est le suivant :
 * 	---------------> L'option 'boxinline' est activée alors le décorateur arrange les boites à cocher de sorte à minimiser l'espace vertical
 * ----------------> L'option 'boxinline' n'est pas spécifiée l'arrangement des boites à cocher minimise l'espace horizontal
 *
 * @author	francoisespinet
 * @param $aOption['autoFormatList'] permet de generer une liste de taille raisonnable (avec retour à la ligne etc...) activée par défault
 */
class Projet_Form_Decorator_MultiCheckBox extends Zend_Form_Decorator_Abstract {

	protected $autoFormatList	= true;
	//le nombre de colones maximales lors d'un disposition en ligne
	const NUMBER_COLS		 	= 4;
	//le nombre de ligne maximales lors d'un dispo en colones
	const NUMBER_LIGNES			= 4;
	
	/**
	 * @brief	Construit une liste d'élement de boites à cocher
	 *
	 * !! ne construit pas les fioritures autour de la liste
	 *
	 * @see Projet_Form_Decorator_Abstract::render_element()
	 * @author		francoisespinet
	 * @version		12 mars 2012 - 15:15:09
	 *
	 */
	public function render($sContent) {
		$oElement = $this->getElement();
		//le nom du champ
		$sName = $oElement->getName();
		//indexe pour savoir quelle box est traitée
		$i=0;
		//differentes box à construire
		$aOptions = $oElement->getMultiOptions();
		if (count($aOptions) == 0) {
			return $sContent.Projet_DataHelper::translate('message.noChoix.short');
		}
		//le nombre de box à construire
		$nBoxes = count($aOptions);
		//les box qui doivent être cochées
		$aActive = $oElement->getValue();
		//création de la table pour l'alignement
		$sTable = "<table cellspacing=2px align=left id=MultiCheckBox-$sName class='MultiCheckBox'>";
		//Creation de la première ligne pour initialisation
		$oRow = new Projet_Xml('tr');
		//parcours des boites a cocher demandées
		foreach ($aOptions as $iValue=>$sOption) {
			$i++;//traitement de la ième box
			//création de la cellule
			$sCell = "<td>";
			//on construit l'input (la boite à cocher), on lui met un id pour la reconnaitre
			$oInput = new Projet_Xml('input',array('name'	=> $sName."[]", 
												  'type'	=> 'checkbox', 
												  'value'	=> $iValue,
												  'id'		=> $sName."-".$iValue));
			//si elle doit être cochée, on la coche
			if ($aActive !== null && in_array($iValue, $aActive)) {
				$oInput->setAttr('checked', 'checked');
			}
			//on construit le label
			$oLabel = new Projet_Xml('label', array( 'for' => $sName."[]"), $sOption);
			
			//on ajoute le tout à la cellule courante
			$sCell .= $oInput->render().$oLabel->render()."</td>";
			//$oLabel->append($oInput);
			//$sCell .= $oLabel->render();
			/* Paramètre d'auto formattage, sauf si spécifié différement, il est true par default
			 * si on demamde un 'boxinline' alors les éléments seront rendus en ligne, de sorte à prendre le moins de place verticalement
			 * si on ne le demande pas, les éléments seront rendus en colone, de sorte à minimiser l'espace horizontal
			 */
			if ($this->autoFormatList) {
				if($oElement->getInline()) {
					if ($i % self::NUMBER_COLS == 0 || $nBoxes < self::NUMBER_COLS || $i == $nBoxes) {
						//si on est au nombre de colones requis, alors on pass à une autre ligne en ajoutant l'actuelle à la table
						$oRow->addData($sCell);
						$sTable .= $oRow->render();
						$oRow = new Projet_Xml('tr');
					} else {
						//sinon on ajoute une cellule
						$oRow->append($sCell);
					}
				} elseif (!$oElement->getInline()) {
					if ($i % (($nBoxes % self::NUMBER_LIGNES)+1) == 0 || $nBoxes < self::NUMBER_LIGNES || $i == $nBoxes) {
						/*
						 * Le nombre de lignes est limité pour des raisons esthétiques (colone trop longues)
						 * aussi le nombre de colones est-il calculé pour remplir le tableau
						 * Le comportement est le même de précedement
						 */
						$oRow->append($sCell);
						$sTable .= $oRow->render();
						$oRow = new Projet_Xml('tr');
					} else {
						$oRow->append($sCell);
					}
				}
			}
			//on réinitialise la cellule
			$sCell = '';
		}
		$sTable .= "</table>";
		//on ajoute un span pour que les élément encapsulants soient de la bonne taille
		$oSpan = new Projet_Xml('span', array(), $sTable);
		$oSpan->setAttrs(array('style' =>'display:inline-block'));
		//on renvoie le tout
		return $sContent.$oSpan->render();
	}
}
