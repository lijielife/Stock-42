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
class Stock_ProduitsController extends Projet_Controller_Action {

	public function indexAction() {
		$this->view->message = "succes du login";
	}
	
	function ajoutAction() {
		$aMap = new Application_Model_Mapper_Categories();
		$this->view->form = new Form_AjoutProduit($aMap->getLibelles());
	}
	
	function consoAction() {
		
	}
	
}