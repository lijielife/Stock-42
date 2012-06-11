<?php
/**
 * Controlleur page d'accueil.
 *
 *
 * @category   Projet
 * @package    modules_main
 * @subpackage controllers
 */
class Stock_AxstockController extends Projet_Controller_Action_Ajax {

	function globalAction() {
		$this->lister(new Application_Service_Produit(), 'listerTousTous');
	}
	
	function fraisAction() {
		$this->lister(new Application_Service_Produit(), 'listerFraisTous');
	}
	
	function nonfraisAction() {
		$this->lister(new Application_Service_Produit(), 'listerNonFraisTous');
	}
	
	function getlibellesbyidcategorieAction() {
		$oMapper = new Application_Model_Mapper_RefItems();
		$oSelect = new Zend_Form_Element_Select(Form_AjoutProduit::LIBELLE);
		$oSelect->setMultiOptions($oMapper->getLibelleByIdCategorie($this->_getParam('idCategorie')))
				->setDecorators(array('ViewHelper'));
		$this->view->element = $oSelect->render();
	}
}