<?php
/** @brief Classe de Rendu XML.
 *
 * 
 */
class Projet_Xml {

	private $nom = '';
	private $attrs = array();
	private $content = array();

	public function __construct($sNom, $aAttrs = array(), $xContent = '') {
		$this->setNom($sNom);
		$this->setAttrs($aAttrs);
		$this->append($xContent);
	}


	public function append($xValue) {
		if (is_array($xValue)) {
			// Concaténation de tableau
			$this->content = array_merge($this->content, $xValue);
		}
		else {
			$this->content[] = $xValue;
		}
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
