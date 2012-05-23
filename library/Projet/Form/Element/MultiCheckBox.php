<?php

/** @brief	Parametrage spécifique
 * @author	francoisespinet
 */
class Projet_Form_Element_MultiCheckBox extends Zend_Form_Element_MultiCheckbox {
	/**
	 * Correspond à l'option de disposition en ligne ou nom
	 * voir Projet_Form_Decorator_MultiCheckBox
	 *
	 * @var	bool
	 */
	protected $_bInline = false;
	protected $_ajax = null;
	//redefinition de la classe de Zend
	public function __construct($spec, $aOptions = array()) {
		if(isset($aOptions['ajax'])) {
			$this->_ajax = $aOptions['ajax'];
			unset($aOptions['ajax']);
		}
		parent::__construct($spec, $aOptions);
	}
	
	public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('MultiCheckBox');
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
            	 ->addDecorator('HtmlTag', array('tag' => 'span', 'id' => 'champ-'.$this->getName()))
            	 ->addDecorator('Errors');
        }
        return $this;
    }
	
	
	/**
	 * @brief		Getter de $this->bInline
	 * @return	the $bInline
	 */
	public function getInline() {
		return $this->_bInline;
	}

	/**
	 * @brief		Setter de $this->bInline
	 * @param	$bInline the $bInline to set
	 */
	public function setInline($bInline = true) {
		$this->_bInline = $bInline;
		return $this;
	}



}
