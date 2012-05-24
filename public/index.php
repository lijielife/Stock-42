<?php
/**
 * @category   Initialisation
 * @package    public
 */

// Define path to root directory
defined('ROOT_PATH')		|| define('ROOT_PATH', realpath(dirname(__FILE__).'/../') );
//define('ROOT_PATH', '.');

// Define path to public directory
defined('PUBLIC_PATH')		|| define('PUBLIC_PATH', ROOT_PATH . '/public');
defined('PHP_LIBRARY_PATH')		|| define('PHP_LIBRARY_PATH', ROOT_PATH . '/library/');

// Define path to application directory
defined('APPLICATION_PATH')	|| define('APPLICATION_PATH', ROOT_PATH . '/application');

// Define path to configs directory
defined('CONFIGS_PATH')		|| define('CONFIGS_PATH', APPLICATION_PATH . '/configs');

// Define path to gabarit directory
defined('GABARIT_PATH')		|| define('GABARIT_PATH',	'/gabarit');
defined('SCRIPTS_PATH')		|| define('SCRIPTS_PATH',	GABARIT_PATH . '/scripts');
defined('STYLES_PATH')		|| define('STYLES_PATH',	GABARIT_PATH . '/styles');
defined('IMAGES_PATH')		|| define('IMAGES_PATH',	GABARIT_PATH . '/images');
defined('LIBRARY_PATH')		|| define('LIBRARY_PATH', 	GABARIT_PATH . '/library');

// Define paths to template directories
defined('TEMPLATE_IMAGES_PATH')		|| define('TEMPLATE_IMAGES_PATH',	GABARIT_PATH . '/images/template');
defined('TEMPLATE_SCRIPTS_PATH')	|| define('TEMPLATE_SCRIPTS_PATH',	SCRIPTS_PATH . '/template');
defined('TEMPLATE_STYLES_PATH')		|| define('TEMPLATE_STYLES_PATH',	STYLES_PATH . '/template');

// Define application environment
defined('APP_ENV')  || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

// On s'assure de la présence de ZendFramework dans l'include path.
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(ROOT_PATH . '/library'), get_include_path()
)));
/** Zend_Application */
require_once "Zend/Application.php";

try {
	// Création de l'objet Zend_Application et lancement du bootstrap.
	$application = new Zend_Application(APP_ENV, CONFIGS_PATH . '/application.ini');
	$application->bootstrap()->run();
	
} catch (Exception $e) {
	// Erreur principale en cas de défaut dans le lancement de Zend_Application.
	$sErrTxt = "<div style='background: -moz-linear-gradient(center top , #F6EAB1, #E47272 8%, #A50C0F) repeat scroll 0 0 transparent;
							border: 1px solid;
							border-color: #C85050 #B42D29 #6F0E08;
							margin: 10px; padding: 0.578em 0.444em 0.389em;
							font-family: \"Trebuchet MS\",\"Lucida Sans Unicode\",\"Lucida Sans\",Arial,Helvetica,sans-serif;
							text-shadow: -1px -1px 0 rgba(0, 0, 0, 0.2);
							color: white; font-size: 1em; font-weight: bold;
							-moz-border-radius: 5px;'>
					<img src='".TEMPLATE_IMAGES_PATH."/icons/web-app/48/Warning.png' alt='Warning' style='float: left;'>
					<br />&nbsp;Erreur: Nous sommes désolés mais il est impossible d'initialiser l'application.<br /><br />
				";

	// Lors d'un lancement de l'application en mode développement, récupération d'informations supplémentaires sur l'erreur.
	if (APP_ENV != 'production') {
		$sErrTxt .= '<hr /><h3 style="color: #F9DE58">Message:</h3>';
		if (APP_ENV == 'recette') {
			$sErrTxt .= '<pre>';
			$sErrTxt .= $e;
			$sErrTxt .= '</pre>';
		} else {
			$sErrTxt .= '<p>' . $e->getMessage() . '</p>';
			$sErrTxt .= '<div style="border-style: inset; border-color: silver; border-width: 2px; padding-left: 10px;">
							<h3 style="color: #F9DE58">
								Le logiciel est lancé en mode: 
								<span style="color: blue; margin-left: 20px;">
								 ' . APP_ENV . '
								</span>
							</h3>
						</div>';
			$sErrTxt .= '<h3 style="color: #F9DE58">Localisation:</h3>';
			$sErrTxt .= '<p>Fichier: ' . $e->getFile() . '<br /> Ligne: ' . $e->getLine() . '</p>';
			$sErrTxt .= '<h3 style="color: #F9DE58">Trace:</h3>';
			$sErrTxt .= '<pre>' . $e->getTraceAsString() . '</pre>';
			$sErrTxt .= '<h3 style="color: #F9DE58">Renseignements supplémentaires:</h3>';
			$sErrTxt .= '<pre>' . $e->getPrevious() . '</pre>';
		}
	}
	echo $sErrTxt."</div>";
}

function cleanPath($path) {
	$result = array();
	// $pathA = preg_split('/[\/\\\]/', $path);
	$pathA = explode('/', $path);
	if (!$pathA[0])
		$result[] = '';
	foreach ($pathA AS $key => $dir) {
		if ($dir == '..') {
			if (end($result) == '..') {
				$result[] = '..';
			} elseif (!array_pop($result)) {
				$result[] = '..';
			}
		} elseif ($dir && $dir != '.') {
			$result[] = $dir;
		}
	}
	if (!end($pathA))
		$result[] = '';
	return implode('/', $result);
}


