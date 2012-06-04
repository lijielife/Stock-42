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
	 * @brief	Permet d'envoyer les urls pour le helper table à la vue
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
	 * Les actions de CMS sont celles définies par ACTION_LISTER, ...
	 * Le controller calculé est le controller courant préfixé de "ax"
	 * Le module calculé est le module courant
	 *
	 * @author amboise.lafont
	 */
	protected function autoSetRoutesUrls() {
		$module = $this->_request->getModuleName();
		$controller = 'ax'.$this->_request->getControllerName();
	
		$sRoute = $this->getRouteGenerique();
	
		// on utilise getUrl à la place de getUrlGenerique car c'est plus efficace
		$sUrlLister   = $this->getUrl($sRoute, array('action' => self::ACTION_LISTER    , 'controller' => $controller), $module, $controller, self::ACTION_LISTER    );
		$sUrlModifier = $this->getUrl($sRoute, array('action' => self::ACTION_MODIFIER  , 'controller' => $controller), $module, $controller, self::ACTION_MODIFIER  );
		$sUrlCreer    = $this->getUrl($sRoute, array('action' => self::ACTION_CREER     , 'controller' => $controller), $module, $controller, self::ACTION_CREER     );
		$sUrlSuppr    = $this->getUrl($sRoute, array('action' => self::ACTION_DESACTIVER, 'controller' => $controller), $module, $controller, self::ACTION_DESACTIVER);
	
		$this->setUrls($sUrlLister, $sUrlModifier, $sUrlSuppr, $sUrlCreer);
	}
	
	protected function returnListeUrl($sAction = self::ACTION_LISTER) {
		$module = $this->_request->getModuleName();
		$controller = 'ax'.$this->_request->getControllerName();
		
		$sRoute = $this->getRouteGenerique();
		return $this->getUrl($sRoute, array('action' => $sAction    , 'controller' => $controller), $module, $controller, $sAction);
	}
	
	protected function autoSetListeUrl($sAction = null) {
		$this->view->urlliste = $this->returnListeUrl($sAction);
	}
	
	protected function getRouteGenerique() {
		return $this->_request->getModuleName().'-'.self::ROUTE_GENERIQUE;
	}
	
	
	protected function getUrlGenerique($action, $controller) {
		return $this->getUrlRoute($this->getRouteGenerique(), array ('controller' => $controller, 'action' => $action));
	}
	
	
	/**	@brief	Renvoie l'url associé à une route si l'utilisateur a les droits dessus
	 *
	 * La route doit présenter la méthode getDefaults() pour pouvoir éventuellement déterminer
	 * l'action, controller, module nécessaire à la construction de la ressource.
	 *
	 * @param $sRoute nom de la route
	 * @param $aOptions options supplémentaires
	 * @return null si pas autorisé, ou l'url
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
	
	/** @brief	Renvoie l'url associé à la route $sRoute
	 *
	 * Le module, controller, action donnée en param√®tre permettent de déterminer le nom de la ressource
	 * à réactiver
	 * 
	 * @return null si pas autorisé, ou l'url
	 * @author amboise.lafont
	 */
	protected function getUrl($sRoute, $aOptions, $sModule, $sController, $sAction) {
		// on suppose que le tableau defaults comprend le module, controller, action
		//if (Projet_Acl_Acl::defaultIsAllowed(Projet_DataHelper::resource($sModule, $sController, $sAction))) {
			//return '/'.$oRoute->assemble($aOptions, false, true);
			return $this->_helper->Url->url($aOptions, $sRoute);
		//}
		//else {
// 			return null;
//  		}
	}
	
	/** @brief	données entrées dans le formulaire invalide
	 */
	const FORM_INVALID = 0;
	/** @brief	l'entité à créer existe déjà en base
	 */
	const FORM_DOUBLON = 1;
	/** @brief	données validées et enregistrer
	 */
	const FORM_SUCCES = 2;
	/** @brief	RAS (pas de submit en cours)
	 */
	const FORM_RIEN = 3;
	
	/** @brief	méthode générale pour traiter les formulaires de création
	 *
	 * La méthode effectue la vérification des donnés par le formulaire et
	 * utilise les méthodes getService, getSvcSaveMethode du formulaire pour
	 * enregistrer en base
	 *
	 * Les messages sont passés à la vue par la propriété message.
	 *
	 * @param $oForm formulaire à traiter
	 * @param $msgEdition message à afficher en cas d'édition
	 * @param $msgSucces message à afficher en cas de succ√®s
	 * @param $msgInvalid message à afficher en cas de formulaire rempli invalidement
	 *
	 * @return FORM_SUCCES || FORM_INVALID || FORM_RIEN || FORM_DOUBLON
	 *
	 * @author amboise.lafont
	 */
	protected function formCreer(Projet_Form $oForm, $msgEdition = '', $msgSucces = 'form.message.succes', $msgInvalid = 'form.message.invalid') {
		// Vérification de la réponse au formulaire.
		if ($this->getRequest()->isPost()) {
			if( $oForm->isValid($this->getRequest()->getPost() ) ) {
				// Lorsque la réponse est valide, on proc√®de à l'enregistrement.
				$sSaveMethode = $oForm->getSvcSaveMethode();
				try {
					$oForm->getService()->$sSaveMethode($oForm->getValues());
					$this->view->message = $msgSucces;
					// on ne donne pas le formulaire à la vue
					return self::FORM_SUCCES;
	
				} catch (Projet_Exception_Doublon $e) {
					$this->view->errors = true;
					$this->view->message = $msgInvalid;
					$ret = self::FORM_DOUBLON;
				} catch (Exception $e) {
					if (APP_DEBUG) {
						throw new Zend_Exception('FORM_SAVE', "cf service form Save ou équivalent", $e);
					} else {
						$this->view->message = 'message.enregistrement.echec';
						return self::FORM_INVALID;
					}
				}
			} else  {
				$this->view->errors = true;
				// Lorsque la réponse est invalide, un message est donné au Layout qui le détecte et l'affiche.
				$this->view->message = $msgInvalid;
				$ret = self::FORM_INVALID;
			}
		} else {
			// Lorsque la réponse n'existe pas, un message est donné au Layout qui le détecte et l'affiche.
			$this->view->message = $msgEdition;
			$ret = self::FORM_RIEN;
		}
		// On donne le formulaire à la vue.
		$this->view->form = $oForm;
		return $ret;
	}
	
	
	/** @brief	méthode générale pour traiter les formulaires d'édition
	 *
	 * Appelle formCreer
	 * Si l'id n'est pas fourni, la méthode utilise $this->getDefaultParamId();
	 *
	 * @see formCreer
	 *
	 * @param $oForm formulaire à traiter
	 * @param $nId id de l'entité à modifier
	 * @param $msgEdition message à afficher en cas d'édition
	 * @param $msgSucces message à afficher en cas de succ√®s
	 * @param $msgInvalid message à afficher en cas de formulaire rempli invalidement
	 *
	 * @return FORM_SUCCES || FORM_INVALID || FORM_RIEN || FORM_DOUBLON
	 *
	 * @author amboise.lafont
	 */
	protected function formModifier(Projet_Form $oForm, $nId=null, $msgEdition = '', $msgSucces = 'form.message.succes', $msgInvalid = 'form.message.invalid') {
		$nId || $nId = $this->getDefaultParamId();
	
		$ret = $this->formCreer($oForm, $msgEdition, $msgSucces, $msgInvalid);
	
		//on n'affiche pas le formulaire en cas de succ√®s
		if ($ret != self::FORM_SUCCES) {
			$sDataMethode = $oForm->getSvcDataMethode();
			$oForm->populate($oForm->getService()->$sDataMethode($nId));
		}
	
		return $ret;
	}
	
	
	
}