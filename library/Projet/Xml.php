<?php
/** @brief Classe de Rendu XML.
 *
 * 
 */
class Projet_Xml {

	private $nom = '';
	private $attrs = array();
	private $content = array();
	const PREPEND = 'prepend';
	const APPEND = 'append';
	
	public function __construct($sNom, $aAttrs = array(), $xContent = '') {
		$this->setNom($sNom);
		$this->setAttrs($aAttrs);
		$this->append($xContent);
	}

	
	protected function _add($xValue, $sPlacement) {
		if ($sPlacement == self::PREPEND) {
			if (is_array($xValue)) {
				// Concaténation de tableau
				$this->content = array_merge($xValue, $this->content);
			}
			else {
				$this->content = array_merge(array($xValue), $this->content);
			}
		} else {
			if (is_array($xValue)) {
				// Concaténation de tableau
				$this->content = array_merge($this->content, $xValue);
			}
			else {
				$this->content[] = $xValue;
			}
		}
	}
	
	public function prepend($xValue) {
		$this->_add($xValue, self::PREPEND);
	}
	
	public function append($xValue) {
		$this->_add($xValue, self::APPEND);
	}

	protected function renderAttrs () {
		$html = '';
		foreach ($this->attrs as $cle => $val) {
			$html .= ' '.$cle.'="'.$val.'"';
		}
		return $html;
	}

	protected function renderContent () {
		return join ($this->content);
	}


	public function render() {
		$sContent = $this->renderContent();
		$html = '<'.$this->nom.$this->renderAttrs();

		if ($sContent == '') {
			return $html.' />';
		}
		else {
			return $html.'>'.$sContent.'</'.$this->nom.'>';
		}
	}

	public function setAttr($sAttr, $sVal) {
		$this->attrs[strtolower($sAttr)] = htmlspecialchars($sVal);
	}

	public function setAttrs ($aAttrs) {
		$this->attrs = $aAttrs;
	}

	public function getAttrs() {
		return $this->attrs;
	}

	/**
	 * @deprecated
	 * @param unknown_type $sAttr
	 * @param unknown_type $sVal
	 */
	public function setAttribute($sAttr, $sVal) {
		$this->setAttr($sAttr, $sVal);
	}
	
	/**
	 * @deprecated
	 * @param unknown_type $xValue
	 */
	public function setData($xValue) {
		$this->append($xValue);
	}
	public function setNom($sNom) {
		$this->nom = strtolower($sNom);
	}

	public function getNom() {
		return $this->nom;
	}

	public function getContent() {
		return $this->content;
	}

	public function __toString() {
		return $this->render();
	}


}
