<?php

class Projet_Form_Element_Select extends Zend_Form_Element_Select {

	protected $_ajax = null;
	//redefinition de la classe de Zend
	public function __construct($spec, $aOptions = array()) {
		if(isset($aOptions['ajax'])) {
			$this->_ajax = $aOptions['ajax'];
			unset($aOptions['ajax']);
			$this->setRegisterInArrayValidator(false);
		}
		parent::__construct($spec, $aOptions);
	}
	
	public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this//->addDecorator('ErreurPrimer')
            	->addDecorator('ViewHelper');
            	//->addDecorator('Erreurs');
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
            	 ->addDecorator('Paragraphe')
            	 ->addDecorator('Errors');
        }
        return $this;
    }
    
     public function render(Zend_View_Interface $view = null) {
		if(!count($this->getMultiOptions())) {
			$this->addMultiOption(0, $this->getView()->translate('message.noResult.short'));
		}
		return parent::render($view);
     }
}
