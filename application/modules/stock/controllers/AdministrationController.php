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

	public function ajouteritemAction() {
		$oMapper = new Application_Model_Mapper_RefCategories();
		$oMapperConservation = new Application_Model_Mapper_RefConservations();
		$oMapperMesure = new Application_Model_Mapper_RefMesures();
		$this->formCreer(new Form_AjoutItem($oMapper->getLibelles(), $oMapperConservation->getLibelles(), $oMapperMesure->getLibelles()));
	}
	
}