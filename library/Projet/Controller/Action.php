<?php
class Projet_Controller_Action extends Zend_Controller_Action {
	
	protected $_flashMessenger = null;
	
	public function preDispatch() {
		if (Zend_Auth::getInstance()->hasIdentity()) {
			// If the user is logged in, we don't want to show the login form;
			// however, the logout action should still be available
			if ('logout' == $this->getRequest()->getActionName()) {
				$this->_helper->redirector->gotoRoute(array(), 'main-login');
			}
		} else {
			if ($this->getRequest()->getActionName() == 'verifuser') {
				return;
			}
			if ($this->getRequest()->getActionName() != 'login')  {
				$this->_helper->redirector->gotoRoute(array(), 'main-login');
				$this->view->placeholder('message')->set("Veuillez vous enregistrer");
			}
			
		}
	}
	
	public function init() {
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$messages = $this->_helper->flashMessenger->getMessages();
		if(!empty($messages)) {
			$this->_helper->layout->getView()->messages = $messages;
		}
		$this->initView();
	}
	
	
	/**
	 * Section concernant les routes
	 * 
	 */
	
	
	
	/**
	 * @brief	Permet d'envoyer les urls pour le helper table √† la vue
	 *
	 *
	 * @author	francoisespinet
	 * @version 2 mars 2012 - 14:11:25
	 * @param string $sUrlListe
	 * @param string $sUrlEdit
	 * @param string $sUrlSuppr
	 */
	protected function setUrls($sUrlListe, $sUrlEdit = null, $sUrlSuppr = null, $sUrlCreer = null) {
		$this->view->urlliste = (string) $sUrlListe;
		$this->view->urledit = (string) $sUrlEdit;
		$this->view->urlsuppr = (string) $sUrlSuppr;
		$this->view->urlcreer = (string) $sUrlCreer;
	}
	
	
	const ACTION_LISTER = 'lister';
	const ACTION_MODIFIER = 'modifier';
	const ACTION_CREER = 'creer';
	const ACTION_DESACTIVER = 'desactiver';
	
	const ROUTE_GENERIQUE = 'generic';
	
	/** @brief	configure automatiquement les urls de CMS via setUrls
	 *
	 * Les actions de CMS sont celles d√©finies par ACTION_LISTER, ...
	 * Le controller calcul√© est le controller courant pr√©fix√© de "ax"
	 * Le module calcul√© est le module courant
	 *
	 * @author amboise.lafont
	 */
	protected function autoSetRoutesUrls() {
		$module = $this->_request->getModuleName();
		$controller = 'ax'.$this->_request->getControllerName();
	
		$sRoute = $this->getRouteGenerique();
	
		// on utilise getUrl √† la place de getUrlGenerique car c'est plus efficace
		$sUrlLister   = $this->getUrl($sRoute, array('action' => self::ACTION_LISTER    , 'controller' => $controller), $module, $controller, self::ACTION_LISTER    );
		$sUrlModifier = $this->getUrl($sRoute, array('action' => self::ACTION_MODIFIER  , 'controller' => $controller), $module, $controller, self::ACTION_MODIFIER  );
		$sUrlCreer    = $this->getUrl($sRoute, array('action' => self::ACTION_CREER     , 'controller' => $controller), $module, $controller, self::ACTION_CREER     );
		$sUrlSuppr    = $this->getUrl($sRoute, array('action' => self::ACTION_DESACTIVER, 'controller' => $controller), $module, $controller, self::ACTION_DESACTIVER);
	
		$this->setUrls($sUrlLister, $sUrlModifier, $sUrlSuppr, $sUrlCreer);
	
	
	}
	
	protected function getRouteGenerique() {
		return $this->_request->getModuleName().'-'.self::ROUTE_GENERIQUE;
	}
	
	
	protected function getUrlGenerique($action, $controller) {
		return $this->getUrlRoute($this->getRouteGenerique(), array ('controller' => $controller, 'action' => $action));
	}
	
	
	/**	@brief	Renvoie l'url associ√© √† une route si l'utilisateur a les droits dessus
	 *
	 * La route doit pr√©senter la m√©thode getDefaults() pour pouvoir √©ventuellement d√©terminer
	 * l'action, controller, module n√©cessaire √† la construction de la ressource.
	 *
	 * @param $sRoute nom de la route
	 * @param $aOptions options suppl√©mentaires
	 * @return null si pas autoris√©, ou l'url
	 * @author amboise.lafont
	 */
	protected function getUrlRoute($sRoute, $aOptions = array()) {
	
		$oRoute = $this->getFrontController()->getRouter()->getRoute($sRoute) ;
		$aDefaults = $oRoute->getDefaults();
	
		if (isset($aOptions['module'])) {
			$sModule = $aOptions['module'];
		}
		else {
			$sModule = $aDefaults['module'];
		}
	
		if (isset($aOptions['controller'])) {
			$sController = $aOptions['controller'];
		}
		else {
			$sController = $aDefaults['controller'];
		}
	
		if (isset($aOptions['action'])) {
			$sAction = $aOptions['action'];
		}
		else {
			$sAction = $aDefaults['action'];
		}
	
		return $this->getUrl($sRoute, $aOptions, $sModule, $sController, $sAction);
	}
	
	/** @brief	Renvoie l'url associ√© √† la route $sRoute
	 *
	 * Le module, controller, action donn√©e en param√®tre permettent de d√©terminer le nom de la ressource
	 *
	 * @return null si pas autoris√©, ou l'url
	 * @author amboise.lafont
	 */
	protected function getUrl($sRoute, $aOptions, $sModule, $sController, $sAction) {
		// on suppose que le tableau defaults comprend le module, controller, action
		if (Projet_Acl_Acl::defaultIsAllowed(Projet_DataHelper::resource($sModule, $sController, $sAction))) {
			//return '/'.$oRoute->assemble($aOptions, false, true);
			return $this->_helper->Url->url($aOptions, $sRoute);
		}
		else {
			return null;
		}
	}
	
	
	
	
}