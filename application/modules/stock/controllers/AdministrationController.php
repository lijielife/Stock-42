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
class Stock_AdministrationController extends Projet_Controller_Action {

	public function indexAction() {
		$this->view->message = "succes du login";
	}
	

	public function ajoutercategorieAction() {
		$this->formCreer(new Form_AjoutCategorie());
	}

	
	
}