<?php

class Projet_Form_Element_TextArea extends Zend_Form_Element_Textarea {

	protected $_ajax = null;
	//redefinition de la classe de Zend
	public function __construct($spec, $aOptions = array()) {
		if(isset($aOptions['ajax'])) {
			$this->_ajax = $aOptions['ajax'];
			unset($aOptions['ajax']);
		}
		if (! $this->getAttrib('cols')) {
			$this->setAttribs(array('cols'	=> 50,
									'rows'	=> 5));
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
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
            	 ->addDecorator('Paragraphe', array('notInline' => 'Champ-TextArea'))
            	 ->addDecorator('Errors');
        }
        return $this;
    }

}
