<?php
/**
 * Controlleur page d'accueil.
 *
 *
 * @category   Projet
 * @package    modules_main
 * @subpackage controllers
 */
class Stock_StockController extends Projet_Controller_Action {

	public function indexAction() {
		$this->view->message = "succes du login";
	}
	
	function globalAction() {
		$oService = new Application_Service_Produit();
		$this->view->aCols = $oService->listerCols();
		$this->autoSetListeUrl('global');
	}
	
	function fraisAction() {
		$oService = new Application_Service_Produit();
		$this->view->aCols = $oService->listerCols();
		$this->autoSetListeUrl('frais');
	}
	
	function nonfraisAction() {
		$oService = new Application_Service_Produit();
		$this->view->aCols = $oService->listerCols();
		$this->autoSetListeUrl('nonfrais');
	}
	
	function autresAction() {
	
	}
}