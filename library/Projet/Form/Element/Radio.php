<?php

class Projet_Form_Element_Radio extends Zend_Form_Element_Radio {

	protected $_ajax			= null;
	protected $_dispoRestreinte	= false;
	protected $_bInline			= false;
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
        	
            $this->addDecorator('ViewHelper');
            $this->addDecorator('Label');
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
           		 ->addDecorator('HtmlTag', array('tag' => 'span', 'class' => CSS_LABEL))
            	 ->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'champ-'.$this->getName()))
            	 ->addDecorator('Errors');
        }
        return $this;
    }
	/**
	 * @brief		Getter de $this->_bInline
	 * @return	the $_bInline
	 */
	public function getInline() {
		return $this->_bInline;
	}

	/**
	 * @brief		Setter de $this->_bInline
	 * @param	$_bInline the $_bInline to set
	 */
	public function setInline($_bInline = true) {
		$this->_bInline = $_bInline;
		return $this;
	}


}
