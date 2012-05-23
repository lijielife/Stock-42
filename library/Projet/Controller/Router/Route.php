<?php
/**
 * @brief	Route similaire à Zend_Controller_Router_Route
 *
 * Elle se chaine via Projet_Controller_Router_Route_Chain
 *
 * @see Projet_Controller_Router_Route_Chain
 *
 * @author amboise.lafont
 */
class Projet_Controller_Router_Route extends Zend_Controller_Router_Route {


	/**
	 * @brief Instantiates route based on passed Zend_Config structure
	 *
	 * copie colle du code de Zend_Controller_Router_Route
	 * appelé par Zend_Controller_Router_Route_Rewrite::_getRouteFromConfig
	 * pour construire automatiquement une instance.
	 *
	 * permet de construire la bonne route lors de la lecture du fichier ini de routes
	 * par exemple
	 *
	 * @param Zend_Config $config Configuration object
	 * @see Zend_Controller_Router_Route_Rewrite
	 */
	public static function getInstance(Zend_Config $config)
	{
		$reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
		return new self($config->route, $defs, $reqs);
	}

	/**
	 * Create a new chain
	 *
	 * Copier coller du code de Zend, à l'exception près que l'on crée un Projet_Controller_Router_Route_Chain
	 *
	 * @param  Projet_Controller_Router_Route_Chain $route
	 * @param  string                                $separator
	 * @return Projet_Controller_Router_Route_Chain
	 */
	public function chain(Zend_Controller_Router_Route_Abstract $route, $separator = '/')
	{

			$chain = new Projet_Controller_Router_Route_Chain();
			$chain->chain($this)->chain($route, $separator);

			return $chain;
	}

}
