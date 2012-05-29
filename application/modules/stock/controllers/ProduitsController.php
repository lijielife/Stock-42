<?php
/**
 * Controlleur page d'accueil.
 *
 *
 * @category   Projet
 * @package    modules_main
 * @subpackage controllers
 */
class Stock_ProduitsController extends Projet_Controller_Action {

	public function indexAction() {
		$this->view->message = "Administration";
	}
	
	public function ajoutAction() {
		$oMapper = new Application_Model_Mapper_Categories();
		$this->formCreer(new Form_AjoutProduit($oMapper->getLibelles()));
	}
}