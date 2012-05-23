<?php
class Form_Login extends Projet_Form {
	
	const LOGIN = 'login';
	const PASSWD= 'passwd';
	
	public function __construct() {
		parent::__construct('FormLogin');
		$this->setAction('authentification')
			 ->setAttrib('id', 'FormLogin');
		$this->createForm();
	}
	
	public function createForm() {
		$oUserName = new Projet_Form_Element_Text('login');
		$oUserName->setRequired(true)->setLabel("Nom d'utilisateur");
		
		$oPassWd = new Projet_Form_Element_Password('passwd');
		$oPassWd->setRequired(true)->setLabel("Mot de Passe");
		
		$oLogin = new Projet_Form_Element_Submit('SubmitLogin');
		$oLogin->setLabel('Se Connecter');
		
		$this->addElements(array($oUserName, $oPassWd, $oLogin));
		$this->setDefaultDecorators();
		$this->setAttrib('style', 'width : 25em;');
	}
}