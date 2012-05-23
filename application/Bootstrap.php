<?php
/**
 * Fichier de lancement des outils et des configurations de l'application.
 *
 * @category   Initialisation
 * @package    application
 * @subpackage Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	 * Variable d'environnement.
	 *
	 * @var string $env Current environment
	 */
	protected $env;

	protected function _initConstantes() {
		Application_Service_Constantes::css();
	}
	/**
	 * Initialise l'environnement, la racine et la configuration.
	 */
	protected function _initEnv() {
		
		// Récupération de l'environnement de l'application
		Zend_Form_Element_File::$this->env = $this->getEnvironment();

		// Récupération des paramètres de l'application
		$aApp = $this->getOption('app');

		// Récupération des choix d'options pour l'environnement applicatif.
		define('APP_NAME',		$aApp['name']);
		define('APP_VERSION',	$aApp['version']);
		define('APP_DEBUG',		$aApp['debug']);
		define('APP_CACHE',		$aApp['cache']);
		define('APP_MAIL',		$aApp['mail']);
		define('APP_ZFDEBUG',	$aApp['zfdebug']);
		
		// Configuration du chemin relatif au stockage des fichiers
		define('UPLOAD_PATH',	$aApp['upload_path']);

	}
	
	
	
	/**
	 * Démarrage du gestionnaire de caches, on récupère chaque configuration.
	 * Par défaut on met en cache les métadata de la bdd
	 *
	 * @return void.
	 */
	protected function _initCache() {
		if ((bool) APP_CACHE) {
			// Récupération des ressources du gestionnaire du cache
			$this->bootstrap('cachemanager');
			
			// Activation du cache du Zend_Translate
			Zend_Translate::setCache($this->getResource('cachemanager')->getCache('translate'));

			// Activation du cache de la base de données
			Zend_Db_Table_Abstract::setDefaultMetadataCache($this->getResource('cachemanager')->getCache('default'));
		}
	}

	/**
	 * Initialisation en registry des éléments permettant d'accéder
	 * au log, au cache et à l'adaptater db dans toute l'application
	 * par le registry
	 *
	 * NOTE: le nom doit être différent de _initDb, sinon la resource Db n'est pas
	 * configuré automatiquement par Zend
	 *
	 * @return void
	 */
	protected function _initDbAdapter() {
		putenv('NLS_DATE_FORMAT="DD/MM/YYYY HH24:MI:SS"');
		putenv('NLS_LANG=FRENCH_FRANCE.UTF8');

		$this->bootstrap('db');

		$oPluginDb = $this->getPluginResource('db');
		
		// Stockage de l'adapter
		define("APP_PDO_ADAPTER", strtoupper($oPluginDb->getAdapter()));
		
		// Format des dates
		if (strtoupper(APP_PDO_ADAPTER) == "PDO_OCI") {
			// Gestion des dates sous Oracle............................# ATTENTION A LA CASSE ! c'est bien [dd] et non [DD] et c'est bien [YYY] et non [YY] ou [YYYY] !!!
			define("PDO_DATE_FORMAT",		"dd/MM/YYY");				# Base..........Format Zend_Date pour afficher une DATE en base Oracle
			define("ZEND_DATE_FORMAT",		"yyyy-MM-dd");				# Zend_Date.....Format Zend_Date pour enregistrer une DATE en base Oracle
			define("PDO_DATETIME_FORMAT",	"dd/MM/YYYY HH24:mi:ss");		# Base..........Format Zend_Date pour afficher un TIMESTAMP en base Oracle
			define("ZEND_DATETIME_FORMAT",	"yyyy-MM-dd HH:mm:ss");		# Zend_Date.....Format Zend_Date pour enregistrer un TIMESTAMP en base Oracle
		} else {
			// Gestion des dates sous MySQL
			define("PDO_DATE_FORMAT",		Zend_Date::ISO_8601);		# Base..........Format Zend_Date pour afficher une DATE en base MySQL
			define("ZEND_DATE_FORMAT",		Zend_Date::ISO_8601);		# Zend_Date.....Format Zend_Date pour enregistrer une DATE en base MySQL
			define("PDO_DATETIME_FORMAT",	Zend_Date::ISO_8601);		# Base..........Format Zend_Date pour afficher un TIMESTAMP en base MySQL
			define("ZEND_DATETIME_FORMAT",	Zend_Date::ISO_8601);		# Zend_Date.....Format Zend_Date pour enregistrer un TIMESTAMP en base MySQL
		}

		
//		$oDb->query("alter session set NLS_DATE_FORMAT='DD/MM/YYYY'");
		
//		$nls = $oDb->fetchAll("select * from NLS_SESSION_PARAMETERS");
//		Zend_Debug::dump($nls);exit;
		
		$oDb = $oPluginDb->getDbAdapter();
		$oDb->setFetchMode(Zend_Db::FETCH_OBJ);
		return $oDb;
	}

	/**
	 * Initialisation du système de traduction.
	 */
	protected function _initTranslatePlugin() {
		// Initialisation du chemin des fichiers de langue
		define("LANGUAGE_PATH", APPLICATION_PATH.'/languages/');

		// Chargement du plugin de Zend_Translate dans la vue
		$this->bootstrap('view');
		$this->bootstrap('frontController');
		$front = $this->getResource('frontController');
		$view = $this->getResource('view');
		//$front->registerPlugin(new Projet_Controller_Plugin_Translate($view));
	}

	/**
	 * Initialisation du routeur.
	 */
// 	protected function _initRouter() {
// 		$f = Zend_Controller_Front::getInstance();

// 		$router = $f->getRouter();
// 		// le site n'est plus accessible par module/controller/action
// 		$router->removeDefaultRoutes();
// 		$router->addConfig(new Zend_Config_Ini(CONFIGS_PATH . '/routes.ini', 'routes'), 'routes');

// 		return $router;
// 	}

	/**
	 * Initialisation des ACLs.
	 *
	 * @return void
	 */
// 	protected function _initAcl() {
// 		// On récupère l'instance du Front Controller.
// 		$this->bootstrap('FrontController');
// 		$oFront = $this->getResource('FrontController');
// 		$oAcl = new Projet_Acl_Acl(CONFIGS_PATH . '/acl.ini');
// 		Projet_Acl_Acl::setDefaultAcl($oAcl);
		
// 		// On intègre le plugin de mise en relation de l'authentification et des ACLs dans le Front Controller.
// 		$oFront->registerPlugin(new Projet_Controller_Plugin_Acl());
// 		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($oAcl);

// 		return $oAcl;
// 	}

	/**
	 * Initialise les helpers d'action.
	 *
	 * @return void
	 */
	protected function _initHelpers() {
		// register the default action helpers.
		Zend_Controller_Action_HelperBroker::addPath('helpers', 'Zend_Controller_Action_Helper');
		// Projet_Controller_Action_Helper.
		// Helper d'action de ZendX pour la completion de code dans les controllers.
		//Zend_Controller_Action_HelperBroker::addPath('ZendX/JQuery/Controller/Action/Helper', 'ZendX_JQuery_Controller_Action_Helper');
	}

	/**
	 * Initialise les vues (view) et le layout de l'application
	 *
	 * @see	modules\main\layouts\layout.phtml
	 * @return void
	 */
	protected function _initViewRenderer() {

		$oViewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$this->bootstrap('view');

		$oViewRenderer->setView($this->getResource('view'));
	
		// Les différents helpers de vues
		if (false === $oViewRenderer->view->getPluginLoader('helper')->getPaths('ZendX_JQuery_View_Helper')) {
			$oViewRenderer->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		}
		if (false === $oViewRenderer->view->getPluginLoader('helper')->getPaths('views_helpers')) {
			$oViewRenderer->view->addHelperPath('views/helpers', 'views_helpers');
		}
		if (false === $oViewRenderer->view->getPluginLoader('helper')->getPaths('Projet_View_Helper')) {
			$oViewRenderer->view->addHelperPath('Projet/View/Helper', 'Projet_View_Helper');
		}

		if (false === $oViewRenderer->view->getPluginLoader('helper')->getPaths('Layout_Helper')) {
			$oViewRenderer->view->addHelperPath('layouts/helpers', 'Layout_Helper');
		}
		
		// le Titre de l'application
		$oViewRenderer->view->headTitle(APP_NAME);
		$oViewRenderer->view->headTitle()->setSeparator(' - ');

		// Les liens d'en-tête Favicon big
		$oViewRenderer->view->headLink(array('rel' => 'icon',
											 'href' => GABARIT_PATH . '/images/favicon-large.png',
											 'type' => 'image/png'),
									   'PREPEND');

		// Les liens d'en-tête
		$oViewRenderer->view->headLink(array('rel' => 'shortcut icon',
											 'href' => GABARIT_PATH . '/images/favicon.ico'),
									   'PREPEND');

		/* @var $this->view Zend_View_Interface */
		// Les différents scripts de l'application
		//$oViewRenderer->view->headScript()->appendFile();

										  
		// Les styles globaux
		$oViewRenderer->view->headLink()->appendStylesheet(STYLES_PATH.'/base.css')
										->appendStylesheet(STYLES_PATH.'/form.css');

		// Initialise le menu Zend_Navigation qui n'existe pas par défaut
		//$aMenu = $this->getOption('navigation');
		//$oMenu = new Zend_View_Navigation($aMenu['MenuSection'], $aMenu['XmlPath'] . "/" . $aMenu['MenuXml'] . ".xml");
		
		//$oViewRenderer->view->navigation($oMenu->getMenu());
		
		$view = $this->bootstrap('layout')->getResource('layout')->getView();
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/layouts/scripts/navigation.xml', 'menu');
		$view->navigation(new Zend_Navigation($config));
	}

	/**
	 * Barre de ZF Debug en jQuery dans l'application.
	 * Nécessite les librairies jQuery et la librairie ZFDebug
	 * actuellement dans le dossier 'library' de l'application.
	 *
	 * On le déclare comme un plugin.
	 *
	 * @brief Initialisation de la barre ZF Debug
	 * @return void
	 */
	protected function _initZFDebug() {

		if ((bool) APP_ZFDEBUG) {
			// Les options de ZFDebug dans un array.
			$options = array('plugins' => array('Variables',
												'File' => array('base_path' => APPLICATION_PATH . "/../"),
												'Memory',
												'Time',
												'Registry',
												'Exception')
			);

			// Instanciation du plugin sur la bdd
			if ($this->hasResource('db')) {
				$this->bootstrap('db');
				$db = $this->getResource('db');
				$options['plugins']['Database']['adapter'] = $db;
			}

			// Configuration du cache plugin
			if ($this->hasResource('cache')) {
				$this->bootstrap('cache');
				$cache = $this->getResource('cache');
				$options['plugins']['Cache']['backend'] = $cache->getBackend();
			}

			// On instancie la classe Debug.
			$debug = new ZFDebug_Controller_Plugin_Debug($options);

			// On déclare le plugin dans le frontcontroller.
			$f = Zend_Controller_Front::getInstance();
			$f->registerPlugin($debug);
		}
	}
	
}
