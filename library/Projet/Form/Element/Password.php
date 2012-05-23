<?php

class Projet_Form_Element_Password extends Zend_Form_Element_Password {

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
        	$this->addDecorator('ErreurPrimer')
           		->addDecorator('ViewHelper')
            	->addDecorator('Erreurs');
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
            	 ->addDecorator('Paragraphe')
            	 ->addDecorator('Errors');
        }
        return $this;
    }

}
