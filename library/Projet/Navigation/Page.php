<?php
/** @brief	Même utilisation que Zend_Navigation_Page_Mvc avec ajout de fonctionnalités
 *
 * Fonctionnalités ajoutées :
 * - isActive reconnait si la page est active à partir de la seule route (plus besoin de spécifier
 * 	le module, le controller, l'action)
 * - getHref() renvoie null si la route n'est pas définie (par cohérence avec le helper Navigation_Menu::htmlify)
 * - getResource() calcule automatiquement le nom de la resource si celle-ci n'est pas donnée
 * !! il faut que la route présente la méthode getDefaults
 *
 * @category Projet
 * @package Navigation
 * @author amboise.lafont
 */
class Projet_Navigation_Page extends Zend_Navigation_Page_Mvc {
	
	/** @brief Les paramètres de routes ne sont pas remis à 0 par défaut */
	protected $_resetParams = false;
	

    /**
	 * @brief Returns whether page should be considered active or not
     * 
	 * rédéfinition de cette méthode pour prendre en compte les routes.
     *
     * @param  bool $recursive  [optional] whether page should be considered
     *                          active if any child pages are active. Default is
     *                          false.
     * @return bool             whether page should be considered active or not
     */
    public function isActive($recursive = false)
    {
		// librement inspiré du code de Zend_Navigation_Page_Mvc::isActive
		if (!$this->_active) {
			// Si le nom de cette route est la même que la route courante
			if ($this->_route == Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName()) {
				return $this->_active = true;
			}
		}
		else {
			return true;
		}

		if ($recursive) {
			foreach ($this as $page) {
				if ($page->isActive(true)) {
					return true;
				}
			}
		}

		return false;

		// éventuellement supprimer cette ligne et la remplacer par le code
		// de Zend_Navigation_Page::isActive si l'on veut juste comparer les routes
		// et pas les modules, controller, action... (comportement par défaut de 
		// Zend_Navigation_Page_Mvc). 
		// return parent::isActive($recursive);
    }


	/** @brief	calcul automatique du nom de la resource associé à la route
	 *
	 * Si la resource a été spécifié  dans le navigation, le calcul n'est pas effectué
	 * Utilisation de Projet_DataHelper::resource pour calculer le nom de la ressource.
	 *
	 * !! La route doit avoir la méthode getDefaults
	 *
	 * @see Projet_DataHelper::resource
	 * @return le nom de la ressource, ou null, si la page n'a pas de route
	 */
	public function getResource() {
		if ($this->_resource) {
			// si la ressource a été définie explicitement on la renvoie
			return $this->_resource;
		}
		elseif ($this->_route) {
			$oRoute = Zend_Controller_Front::getInstance()->getRouter()->getRoute($this->_route) ;
			// la route doit avoir la méthode getDefaults
			$aDefaults = $oRoute->getDefaults();
			// on suppose que le tableau defaults comprend le module, controller, action
			return $this->_resource = Projet_DataHelper::resource($aDefaults['module'], $aDefaults['controller'], $aDefaults['action']);
		}
		else {
			return null;
		}
	}

	/** @brief	Renvoie le lien url associé à la route
	 *
	 * la méthode renvoie null si la route n'est pas définie.
	 * Ainsi, l'appel du helper menu->htmlify sur cette page rendrait
	 * un span au lieu d'un a
	 *
	 */
	public function getHref() {
		if ($this->_route) {
			return parent::getHref();
		}
		else {
			return 'javascript:void(0)';
		}
	}
}

