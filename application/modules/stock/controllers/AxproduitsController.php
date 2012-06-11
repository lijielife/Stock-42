<?php
/**
 * Controlleur page d'accueil.
 *
 *
 * @category   Projet
 * @package    modules_main
 * @subpackage controllers
 */
class Stock_AxproduitsController extends Projet_Controller_Action_Ajax {

	public function getunitebyiditemAction() {
		$oMapperUnite = new Application_Model_Mapper_RefMesures();
		$this->view->data = $oMapperUnite->getUniteByIdItem($this->_getParam('idItem'));
	}
}