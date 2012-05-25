<?php

class Projet_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

	
	/**
	 * @var Zend_Controller_Action_Helper_Redirector instance
	 */
	private $_controller;

	const CLE_FAIL_ACL = 'echec.acl';

	/**
	 * Chemin de redirection lors de l'échec d'authentification.
	 */
	const FAIL_AUTH_ROUTE		=	'heliflot-accueil';
	
	/**
	 * Chemin de redirection lors de l'échec de contrôle des privilèges.
	 */
	const FAIL_ACL_ROUTE		=	'heliflot-accueil';
	
	public function __construct() {
		$this->_controller	=	new Zend_Controller_Action_Helper_Redirector();
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request) {

		
		
		
		/*
		// qu'on puisse tester directement le profil
		// Le profil est sauvegardé alors en session
		if ($request->getParam(IDENTITE_ID_PROFIL) && APP_ENV != 'production') {
			$nRole = $request->getParam(IDENTITE_ID_PROFIL);
			Projet_Acl_Acl::setDefaultRole($nRole);
			Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($nRole);
			$oIdent[IDENTITE_ID_PROFIL] = $nRole;
			$this->_auth->getStorage()->write($oIdent);

		}
		else
		*/
#		if (is_array($oIdent) && array_key_exists(IDENTITE_ID_PROFIL, $oIdent)) {
#			$nRole = $oIdent[IDENTITE_ID_PROFIL];
#		} else {
#			$nRole = 2;
#		}
		
		// On intercepte le nom de la ressource (controller) et du privilège demandé (action).

		$sModule 	 = $request->getModuleName();
		$sController = $request->getControllerName();
		$sAction	 = $request->getActionName();

		// La ressource n'existe pas.
		// commenté car la ressource est créé automatiquement si on ne l'a pas déclaré dans acl.ini
#		if (!$this->_acl->has($sResource)) {
#			$this->_controller->gotoSimple(self::FAIL_ACL_ACTION, self::FAIL_ACL_CONTROLLER, self::FAIL_ACL_MODULE, array("messageWarn" => 1));
#		}
		
		// TODO: vérifier qu'on est pas sur la route d'authentification
		// Le profil utilisé n'existe pas ou n'est pas reconnu par l'application.
#		if (!$this->_acl->hasRole($nRole) ) {
#			$this->_controller->gotoRouteAndExit(array("messageWarn" => 2), self::FAIL_AUTH_ROUTE);
#		}
#		if (!$sModule || !$sController || !$sAction) {
#			return;
#			$this->_controller->gotoRouteAndExit(array("messageWarn" => 4), self::FAIL_ROUTE);
#		}
		// La ressource demandée n'est pas accessible pour ce role avec ces privilèges.
		if (!Projet_Acl_Acl::defaultIsAllowed(Projet_DataHelper::resource($sModule, $sController, $sAction))) {
			// si requête ajax
			if ($request->isXmlHttpRequest()) {
				die(Projet_DataHelper::translate(self::CLE_FAIL_ACL));
			}

			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
				->setNamespace('warning')
				->addMessage(self::CLE_FAIL_ACL);
			$this->_controller->gotoRouteAndExit(array(), self::FAIL_ACL_ROUTE);
		}
	}

}
