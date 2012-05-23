<?php

/**
 * Décoration spéciale pour faciliter le traitement en ajax : ajoute un symbol autour du champ
 *
 * @author	francoisespinet
 */
class Projet_Form_Decorator_Ajax extends Zend_Form_Decorator_Abstract {
	
	const PREFIX		= 'PREFIX';
	const SUFFIX		= 'SUFFIX';
	const TAG			= 'tag';
	/*
	 * Le contenu de ces variables sera inséré respectivement avant et aprés le nom du champ pour constituer l'id du symbol entourant
	 */
	protected $_prefix = 'result-';
	protected $_suffix = '';
	
	public function render($sContent) {
		//récuperation des options éventuelles
		$this->getOption(self::PREFIX) && $this->_prefix =  $this->getOption(self::PREFIX);
		$this->getOption(self::SUFFIX) && $this->_suffix = $this->getOption(self::SUFFIX);
		$oElement = $this->getElement();
		//récuperation du tag voulu
		$sTag = (string) $this->getOption(self::TAG);
		//création du symbol voulu
		$oSpan = new Projet_Xml($sTag);
		//définition de l'id
		$oSpan->setAttr('id', $this->_prefix.$oElement->getName().$this->_suffix);
		//ajout du contenu précedent au span
		$oSpan->append($sContent);
		//renvoi de la nouvelle chaine
		return $oSpan->render();
	}
	/**
	 * @brief		Getter de $this->_prefix
	 * @return	the $_prefix
	 */
	public function getPrefix() {
		return $this->_prefix;
	}

	/**
	 * @brief		Setter de $this->_prefix
	 * @param	$_prefix the $_prefix to set
	 */
	public function setPrefix($_prefix) {
		$this->_prefix = $_prefix;
	}

	/**
	 * @brief		Getter de $this->_suffix
	 * @return	the $_suffix
	 */
	public function getSuffix() {
		return $this->_suffix;
	}

	/**
	 * @brief		Setter de $this->_suffix
	 * @param	$_suffix the $_suffix to set
	 */
	public function setSuffix($_suffix) {
		$this->_suffix = $_suffix;
	}

		
}
