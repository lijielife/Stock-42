<?php
/**
 * @brief Route statique (même utilisation que Zend_Controller_Router_Route_Static)
 *
 * correction d'un défaut de Zend_Controller_Router_Route_Static qui ne prend pas
 * en compte les caractères spéciaux html dans l'url (tels que ',é,è,...)
 * !!! Déconseillé d'utiliser des accents dans la route
 *
 * @see        Zend_Controller_Router_Route_Static
 * @author amboise.lafont
 */
class Projet_Controller_Router_Route_Static extends Zend_Controller_Router_Route_Static {

	/**
	 * Prepares the route for mapping.
	 *
	 * défaut corrigé : Zend_Controller_Router_Route_Static::match ne décode
	 * pas l'url donc les caractères accentués ne sont pas bien comparés à la route.
	 * La correction consiste à encoder aussi la route (é => %A9%C9)
	 *
	 * @param string $route Map used to match with later submitted URL path
	 * @param array $defaults Defaults for map variables with keys as variable names
	 */
	public function __construct($route, $defaults = array())
	{
		// tout est dans le urlencode
		parent::__construct(urlencode($route), $defaults);
	}

	/**
	 * @brief Instantiates route based on passed Zend_Config structure
	 *
	 * copie colle du code de Zend_Controller_Router_Route_Static
	 * appelé par Zend_Controller_Router_Route_Rewrite::_getRouteFromConfig
	 * pour construire automatiquement une instance.
	 *
	 * permet de construire la bonne route lors de la lecture du fichier ini de routes
	 * par exemple
	 *
	 * @param Zend_Config $config Configuration object
	 * @see Zend_Controller_Router_Route_Rewrite
	 */
	public static function getInstance(Zend_Config $config) {
		// Copier-coller bête et méchant. Espérons que ça marche
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
		return new self($config->route, $defs);
	}
}
