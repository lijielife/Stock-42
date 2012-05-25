<?php
/**	@brief Plugin d'internationnalisation de l'application.
 * 
 * Plugin permettant d'initialiser la gestion de plusieurs langues dans l'application.
 * 
 */
class Projet_Controller_Plugin_Translate extends Zend_Controller_Plugin_Abstract {
	
	const DEFAULT_LANGUAGE	= "fr";
	const DEFAULT_COUNTRY	= "FR";
	
	protected $_view;

	public function __construct($view) {
		$this->_view = $view;
	}

	/**	@brief Changement de langue.
	 * 
	 * Exploitation du cache de l'application pour stocker l'instance de Zend_Translate correspondant.
	 * Si le cache n'est pas présent, une nouvelle instance est créée, puis stockée dans le cache de Zend_Translate.
	 * 
	 * @see		Controller/Plugin/Zend_Controller_Plugin_Abstract::routeShutdown()
	 * @throws	exception Projet_Exception en cas d'erreur au cours de la requête.
	 * @return	void.
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		parent::routeShutdown($request);
		// Récupération du paramètre de langue passée dans l'URL
		$sLocal = $this->getRequest()->getParam('language') ? $this->getRequest()->getParam('language') : self::DEFAULT_LANGUAGE;
		
		// Récupération du local dans une combinaison du type "fr_FR"
		$aLanguage	= explode("_", $sLocal);
		$sLanguage	= isset($aLanguage[0]) ? $aLanguage[0] : self::DEFAULT_LANGUAGE;	# Récupération de "fr"
		$sCountry	= isset($aLanguage[1]) ? $aLanguage[1] : self::DEFAULT_COUNTRY;		# Récupération de "FR"
		
		// Fonctionnalité réalisée si le fichier de langue n'existe pas
		if (!file_exists(LANGUAGE_PATH.$sLanguage.'.ini')) {
			$sLanguage = self::DEFAULT_LANGUAGE;
		}
		
		// Récupération du cache de la langue en cours dans le cache de Zend_Translate
		if ((bool) APP_CACHE) {
			$oCacheTranslate = Zend_Translate::getCache();
			$oTranslate = $oCacheTranslate->load($sLanguage);
		} else {
			$oTranslate = null;
		}
		
		// Fonctionnalité réalisé si
		if (empty($oTranslate)) {
			// Initialisation de la langue
			try {
				// Chargement du Zend_Translate
				$oTranslate = new Zend_Translate(
					array(
						'adapter'			=> 'ini', 
						'content'			=> LANGUAGE_PATH.$sLanguage.'.ini',
						'locale'			=> $sLocal
					)
				);

				$sFichier = LANGUAGE_PATH.$sLanguage.'.php';
				if (file_exists($sFichier)) {
					//traduction des erreurs de formulaires
					$oTranslate->addTranslation(array('content' =>  new Zend_Translate('array', $sFichier, $sLanguage)));
				}
				
				if ((bool) APP_CACHE) {
					// Enregistrement de la langue dans le cache
					$oCacheTranslate = Zend_Translate::getCache();
					$oCacheTranslate->save($oTranslate, $sLanguage);
				}
			} catch (Exception $e) {
				if ((bool) APP_CACHE) {
					// Suppression du cache
					$oCacheTranslate->remove($sLanguage);
				}
				// Création d'une exception au niveau du projet
				die ($e->getMessage());
				throw new Projet_Exception($e->getMessage(), __METHOD__, $e);
			}
		}
		
		// Enregistrement du Zend_Translate dans les fonctionnalités de l'application
		Zend_Registry::set("Zend_Locale", $sLocal);
		Zend_Registry::set("Zend_Translate", $oTranslate);
		// Potentiellement inutile vu la ligne précédente...
		Zend_Form::setDefaultTranslator($oTranslate);
		Zend_Validate_Abstract::setDefaultTranslator($oTranslate);

		// Language par défaut pour toutes les routes
		Zend_Controller_Front::getInstance()->getRouter()->setGlobalParam('language', $sLocal);
	}
}
