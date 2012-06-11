<?php
/**
 * Controlleur page d'accueil.
 *
 * @remark	ATTENTION : seul contrôleur sans nameSpace "Main_"
 *
 * @category   Projet
 * @package    modules_main
 * @subpackage controllers
 */
class IndexController extends Projet_Controller_Action {

	public function indexAction() {
		$this->view->message = "succes du login";
		$this->view->sousMessage = "Vous vous êtes loggés la dernière fois le : ";
		$oMapper = new Application_Model_Mapper_Users();
		$aIdentity = (array) Zend_Auth::getInstance()->getIdentity();
		$this->view->loginDate = $oMapper->getLastLoginDate($aIdentity['ID']);
	}
	
	
	public function configapacheAction() {
		phpinfo();
 	}
	
}