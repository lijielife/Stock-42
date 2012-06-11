<?php

/**
 * Element DatePicker avec choix des décorateurs par défaut
 *
 * @author	francoisespinet
 * @param
 */
class Projet_Form_Element_DatePicker extends ZendX_JQuery_Form_Element_DatePicker {

	protected $_ajax			= null;
	protected $_dispoRestreinte	= false;
	//redefinition de la classe de Zend
	public function __construct($spec, $aOptions = array()) {
		if(isset($aOptions['ajax'])) {
			$this->_ajax = $aOptions['ajax'];
			unset($aOptions['ajax']);
		
		}
		
		$this->setJqueryParams(array('dateFormat' => "dd/mm/yy",
									 'firstDay'	  => 1))
			 ->addValidator(new Zend_Validate_Date(array('format' => "dd/mm/yy")));
		
		parent::__construct($spec, $aOptions);
	}
	
	public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
        	$this->addDecorator(new ZendX_JQuery_Form_Decorator_UiWidgetElement());
            if ($this->_ajax !== null) {
            	$this->addDecorator('Ajax', array('tag' => $this->_ajax));
            }
            $this->addDecorator('Label')
            	 ->addDecorator('Paragraphe');
            	// ->addDecorator('Errors');
        }
        return $this;
    }


}
