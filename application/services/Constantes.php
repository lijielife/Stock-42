<?php
class Application_Service_Constantes {
	
	
	public static function css() {
		Application_Service_Constantes::_form();
		
	}
	
	
	public static function _form() {
		define('CSS_FORM_ERREUR', 'FormErreur');
		define('CSS_ELEMENT_CLEAR_LEFT', 'clearleft');
		define('CSS_ELEMENT_CLEAR_RIGHT', 'clearright');
		define('CSS_FORM_CHAMP', 'champ');
		define('CSS_FORM_SMALL', 'small');
		define('CSS_FORM_MEDIUM', 'medium');
		define('CSS_FORM_LARGE', 'large');
	}
	
	
	
}