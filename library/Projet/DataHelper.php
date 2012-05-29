<?php
/**	@brief Bibliothèques fonctionnelle.
 * Ensemble de méthodes statiques de manipulation et de mise en forme des données.
 *
 * Bibliothèque de fonctionnalités permettant de manipuler et de mettre en forme des données :
 * 		1. VALIDATION DE VALEURS ;
 * 		2. MANIPULATION DE DATES ;
 * 		3. MANIPULATION DE VARIABLES ;
 * 		4. EXTRACTION DE DONNEES ;
 *
 * @category	Functions
 * @package		Library
 * @subpackage	Projet
 */
class Projet_DataHelper {

	/**
	 * @brief Constantes de date.
	 * Constantes des variables relatives à la manipulation des dates.
	 * @author cedric-1.durand
	 */
	const EPHEMERIDE		= 365.25;		# Moyenne du nombre de jours par année
	const SECONDES_JOUR		= 86400;		# Nombre de secondes par jour : 24h x 3600s

	/**
	 * @brief Typage de valeurs.
	 * Constantes des types de variables.
	 * @author cedric-1.durand
	 */
	const TYPE_NULL			= 'null';
	const TYPE_OBJECT		= 'object';
	const TYPE_ARRAY		= 'array';
	const TYPE_STRING		= 'str';
	const TYPE_MONEY		= 'monetaire';
	const TYPE_FLOAT		= 'float';
	const TYPE_INTEGER		= 'int';
	const TYPE_BOOLEEN		= 'bool';
	const TYPE_REAL			= 'real';
	const TYPE_DATE			= 'date';
	const TYPE_UNDEFINED	= 'undefined';
	
	/**
	 * @brief Tableau exploitable via @strtr().
	 * Constantes des correspondances entre les caractères accentués et leurs équivalent alphabétique.
	 * @var	array
	 * @author cedric-1.durand
	 */
	public static $ARRAY_REPLACE = array(
		'Â' => 'A',
		'À' => 'A',
		'Ä' => 'A',
		'Ã'	=> 'A',
		'â' => 'a',
		'à' => 'a',
		'ä' => 'a',
		'ã'	=> 'a',
		'@'	=> 'a',
		
		'Ĉ'	=> 'C',
		'Ç'	=> 'C',
		'ĉ'	=> 'c',
		'ç'	=> 'c',
	
		'Ê' => 'E',
		'È' => 'E',
		'Ë' => 'E',
		'É' => 'E',
		'€' => 'E',
		'ê' => 'e',
		'è' => 'e',
		'ë' => 'e',
		'é' => 'e',
		'€' => 'e',
	
		'Ĝ'	=> 'G',
		'ĝ'	=> 'g',
	
		'Ĥ'	=> 'H',
		'Ḧ' => 'H',
		'ĥ'	=> 'h',
		'ḧ' => 'h',
	
		'î' => 'i',
		'ï' => 'i',
	
		'Ĵ' => 'J',
		'ĵ' => 'j',
	
		'Ô' => 'O',
		'Ö' => 'O',
		'Õ' => 'O',
		'ô' => 'o',
		'ö' => 'o',
		'õ' => 'o',
	
		'Ŝ' => 'S',
		'ŝ' => 's',
	
		'Û' => 'U',
		'Ù' => 'U',
		'Μ' => 'U',
		'û' => 'u',
		'ù' => 'u',
		'µ' => 'u',
	
		'Ÿ'	=> 'Y',
		'Ỹ'	=> 'Y',
		'ÿ'	=> 'y',
		'ỹ'	=> 'y'
	);

	/**	@brief Récupération de la localisation.
	 * Méthode permettant de récupérer la localisation de l'interface utilisateur.
	 * @return	string localisation du registre Zend_Locale.
	 */
	static function getLocation() {
		return Zend_Registry::isRegistered("Zend_Locale") ? Zend_Registry::get("Zend_Locale") : 'fr';
	}
	
	/**	@brief Formatage de la date selon la localisation.
	 * Méthode permettant de formater la date selon la localisation de l'interface utilisateur.
	 * @return	string localisation du registre Zend_Locale.
	 */
	static function dateLocation($sDate, $bDefaultCurrent = true, $sFormat = PDO_DATE_FORMAT) {
		// Récupération de la langue locale
		$sLocation = self::getLocation();
		
		// Fonctionnalité permettant de générer une date courante si elle n'est pas renseignée
		if ($bDefaultCurrent) {
			$sDefaultDate = !empty($sDate) ? $sDate : Zend_Date::now($sLocation);
		} elseif (!empty($sDate)) {
			$sDefaultDate = $sDate;
		}
		
		// Renvoi du résultat
		if (!empty($sDefaultDate)) {
			// Récupération du format selon la déclaration dans le fichier de langue en cours
			$sFormat = self::translate($sFormat);
			// Génération de la date selon le format
			$oDate = new Zend_Date($sDefaultDate);
			// Formatage selon le fichier si la clé de langage existe, sinon format par défaut
			$dResultat = $oDate->toString($sFormat, null, $sLocation);
		} else {
			$dResultat = null;
		}
		
		// Renvoi du résultat
		return $dResultat;
	}
	
	/**	@brief Traduction d'un élément complexe.
	 * Méthode permettant de réaliser un Translate sur un élément complexe tel un @a array ou une @a stdClass.
	 * @param	array|stdClass|string	$xVariable		: variable à identifier.
	 * @return	string type de variable.
	 */
	static function translate($xData) {
		// Initialisation du résultat
		$xResult = array();
		// Fonctionnalité réalisée si le Zend_Translate est actif
		if (Zend_Registry::isRegistered("Zend_Translate")) {
			// Récupération de l'instance du Zend_Translate
			$oTranslate = Zend_Registry::get("Zend_Translate");
			// Manipulation de la donnée selon son typage
			if ($oTranslate && is_object($xData)) {
				// Manipulation d'une classe
				foreach ($xData as $sKey => $sValue) {
					$xResult->$sKey = self::translate($sValue);
				}
			} elseif ($oTranslate && is_array($xData)) {
				// Manipulation d'un tableau
				foreach ($xData as $sKey => $sValue) {
					$xResult[self::translate($sKey)] = self::translate($sValue);
				}
			} else {
				// Manipulation d'une chaîne
				$xResult = $oTranslate->translate($xData);
			}
		} else {
			// Aucune manipulation de la chaîne
			$xResult = $xData;
		}
		// Renvoi du résultat
		return $xResult;
	}

	/**
	 * @brief concatène deux mots (éventuellement vides)
	 * @param $sM1 premier mot
	 * @param $sM2 second mot
	 * @param $sSeparateur le séparateur entre les deux mots
	 * @return la concaténation des deux, avec un séparateur si nécessaire
	 * @author amboise.lafont
	 */
	static function concatMots($sM1, $sM2, $sSeparateur = ' ') {
		if ($sM1 == '') {
			return $sM2;
		}
		elseif ($sM2 == '') {
			return $sM1;
		}
		else {
			return $sM1.$sSeparateur.$sM2;
		}
	}

	/**
	 * @brief	Permet d'integrer des variables dans une tradution ou chaine de caractères
	 *
	 * Dans la chaine de caractère, les élements a remplacer doivent suivre la syntaxe __ELEMENT__
	 * Le tableau de remplacement est de la forme : ELEMENT => variable
	 *
	 * @author		francoisespinet
	 * @version		20 mars 2012 - 10:12:13
	 * @throws
	 *
	 * @param 	string $sCle une clé de language ou une chaine de caractère
	 * @param	array $aRemplacement tableau associatif de remplacement
	 * @param	optionnel string delimiteur des variables
	 * @return	string avec les variables substituées
	 */
	static function varTranslate($sCle, $aRemplacement, $sDelimiteur = '__') {
		$sChaine = self::translate($sCle);
		$aKeys = array();
		foreach ($aRemplacement as $sVar => $sVarReplace) {
			$aKeys[]=$sDelimiteur.$sVar.$sDelimiteur;
			$aValues[]=$sVarReplace;
		}
		return str_replace($aKeys, $aValues, $sChaine);
	}

	/** @brief	enlève les valeurs null d'un tableau
	 *
	 * array_filter enlève toutes les valeurs assimilables à false
	 * si on ne met pas d'argument, donc l'entier 0 est enlevé par exemple
	 *
	 * @param $aA tableau à filtrer (non modifié)
	 * @return le tableau sans les valeurs null
	 * @author amboise.lafont
	 */
	static function array_filter($aA) {
	   return array_filter($aA, function($v) { return isset($v); });
	}
	 
	/** @brief	renvoie le nom de la ressource ACL associé à un controller
	 *
	 * La convention est la suivante : Heliflot_IndexController::indexAction => heliflot_index_index pour la ressource
	 *
	 * @author amboise.lafont
	 */
	static function resource($module, $controller, $action) {
		return $module.'_'.$controller.'_'.$action;
	}
}
