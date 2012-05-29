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
		$this->lister(new Application_Service_Item(), 'listerTousItem');
	}
}