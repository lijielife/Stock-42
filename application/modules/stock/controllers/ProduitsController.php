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
		$oMapper = new Application_Model_Mapper_RefCategories();
		$this->formCreer(new Form_AjoutProduit($oMapper->getLibellesReels())); 
	}
	
	public function consoAction() {
		$oMapper = new Application_Model_Mapper_RefCategories();
		$this->formCreer(new Form_AjoutProduit($oMapper->getLibelles()));
	}
}