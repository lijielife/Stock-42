<?php
/** @brief	Route de chainage (même utilisation que Zend_Controller_Router_Route_Chain)
 *
 * Ajoute la méhode getDefaults() qui fait défaut à Zend_Controller_Router_Route_Chain
 * !! Cette classe présuppose que toutes les routes chainés qui la constituent ont une méthode
 * getDefaults();
 *
 * TODO: créer la méthode statique getInstance pour qu'on puisse accéder à la classe
 * directement dans un fichier .ini
 *
 * @see Zend_Controller_Router_Route_Chain
 * @author amboise.lafont
 */
class Projet_Controller_Router_Route_Chain extends Zend_Controller_Router_Route_Chain {

	/** @brief	Liste des paramètres par défaut
	 * @see getDefaults
	 */
	protected $_aDefaults = null;


	/** @brief	Renvoie la liste des paramètres par défaut de la chaine
	 *
	 * La méthode parcourt les sous-routes et renvoie tous leurs paramètres par défaut
	 */
	public function getDefaults() {
		// si le calcul a déjà été fait
		if ($this->_aDefaults !== null) {
			// on envoie la sauce
			return $this->_aDefaults;
		}

		$this->_aDefaults = array();

		// parcours de toutes les routes chainées
		foreach ($this->_routes as $route) {
			// ajout des paramètres par défaut de $route->getDefaults()
			$this->_aDefaults = array_merge($this->_aDefaults, $route->getDefaults());
		}

		return $this->_aDefaults;
	}

}
