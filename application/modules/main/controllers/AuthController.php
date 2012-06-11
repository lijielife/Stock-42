<?php
/**
 * Controlleur page d'accueil.
 *
 * @remark	ATTENTION : seul contrôleur sans nameSpace "Main_"
 *
 * @package    modules_main
 * @subpackage controllers
 */
class AuthController extends Projet_Controller_Action {
	
	public function getAuthAdapter(array $params) {
		$oAuth = new Zend_Auth_Adapter_DbTable();
		$oAuth->setTableName('USERS')
			  ->setIdentityColumn('LOGIN')
			  ->setCredentialColumn('MDP')
			  ->setIdentity($params[Form_Login::LOGIN])
    		  ->setCredential($params[Form_Login::PASSWD])
			  ->setCredentialTreatment('MD5(?)');
		
		return $oAuth;
	}
	
	public function loginAction() {
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$this->view->form = "<p> Vous êtes déjà enregistrés </p>";
		}
		$this->view->form = $this->getLoginForm();
	}
	
	public function verifuserAction() {
		$request = $this->getRequest();
		// Check if we have a POST request
		if (!$request->isPost()) {
			return $this->_helper->redirector->gotoRoute(array(), 'main-accueil');
		}
		
		// Get our form and validate it
		$form = $this->getLoginForm();
		if (!$form->isValid($request->getPost())) {
			// Invalid entries
			$this->view->form = $form;
			return $this->render('login'); // re-render the login form
		}
		
		// Get our authentication adapter and check credentials
		$adapter = $this->getAuthAdapter($form->getValues());
		$auth    = Zend_Auth::getInstance();
		$result  = $auth->authenticate($adapter);
		if (!$result->isValid()) {
			// Invalid credentials
			$form->addError('Login ou Mot de Passe faux');
			$this->view->form = $form;
			return $this->render('login'); // re-render the login form
		}
		$auth->getStorage()->write($adapter->getResultRowObject(null, 'MDP'));
		$oMapper = new Application_Model_Mapper_RefLogins();
		$nId = (int) (array) $adapter->getResultRowObject('ID');
		$oMapper->updateLogin($nId);
		$oMapperUser = new Application_Model_Mapper_Users();
		$oMapperUser->updateLastLogin($nId);
		// We're authenticated! Redirect to the home page
		$this->_helper->redirector->gotoRoute(array(), 'main-accueil');
	}
	
	public function getLoginForm() {
		return new Form_Login();
	}
	
	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector->gotoRoute(array(), 'main-login'); // back to login page
	}
	
	public function resumeloginAction() {
		//vérification
		if(Zend_Auth::getInstance()->hasIdentity()) {
			$this->view->aIdent = (array) Zend_Auth::getInstance()->getIdentity();
		}
	}
	public function ajouterutilisateurAction() {
		
	} 
 	
}