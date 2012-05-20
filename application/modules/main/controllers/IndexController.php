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
class IndexController extends Zend_Controller_Action {
	
	protected $aParam = array();
	
	/**
	 * Initialisation du controleur
	 *
	 * @return void
	 */
	//public function init() {
		/* Initialize action controller here */
		//parent::init();
		//$this->aParam = $this->_request->getParams(); # Récupération des paramètres transmis via l'URL
	//}
	
	/**
	 * Action par défaut - affiche l'index.
	 * Affiche un flash info selon les parametres configures dans flashinfo.ini (via interface modalBox)
	 *
	 * @return void
	 */
	public function indexAction() {
		// On place le numéro de page dans la vue pour information de débuggage
		//$this->view->placeholder('numecran')->set("A1.1");
		
	}
}