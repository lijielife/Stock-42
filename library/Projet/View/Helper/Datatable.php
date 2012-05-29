<?php
/** @brief	Affichage d'entités en liste avec possibilités d'actions dans une dernière colonne
 *
 * @see public/gabarit/scripts/projet/datatable.js
 * @see Projet_Liste
 *
 * @category   Projet
 * @package    view
 * @subpackage helper
 * @version    $LastChangedRevision: 810 $
 * @author     $LastChangedBy: amboise.lafont
 */
class Projet_View_Helper_Datatable extends Zend_View_Helper_Abstract {

	/**
	 * Opérations à n'effectuer qu'une seule fois même si le helper est utilisé plusieurs fois
	 * dans la même vue
	 */
	public function __construct () {
		$oView = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

		$this->view = $oView->view;
		// Ajout des fichiers Js et CSS
		$this->appendJs();
		$this->appendCSS();
		// Ajout de la traduction
		$this->appendTranslate();
	}

	/** @brief	Création d'un tableau chargé en ajax json
	 * Helper utilisé pour la création d'une liste
	 * Attention : il doit y avoir un id différent pour chaque utilisation
	 *
	 * L'url $sUrlListe doit alors fournir les donnés au format json suivant :
	 * @code
	 * { "aaData" : [ [Col1, Col2, Col3], [Col1, Col2, Col3] ] }
	 * @endcode
	 *
	 * La dernière colonne doit fournir l'id : elle est utilisée pour la colonne action
	 * le formulaire de l'url $sUrlFormModif est alors appelé avec le paramètre id correspondant
	 *
	 * @param $aCols Liste des noms de colonnes
	 * @param $sUrlListe url utilisé pour récupérer les données du tableau au format JSon
	 * @param $id utilisé pour cibler le tableau
	 */
	public function datatable(array $aCols, $sUrlListe, $id = 'datatable') {
		return new Projet_Liste($this->view, $aCols, $sUrlListe, $id);
	}
	/** @brief	ajoute les clés de language en javascript pour traduire le dataTable
	 */
	protected function appendTranslate() {
		$this->view->headScript()->appendFile(SCRIPTS_PATH . '/projet/dataTable/language-'.Projet_DataHelper::getLocation().'.js');
	}
	
	protected function appendJs() {

		$this->view->headScript()->appendFile(LIBRARY_PATH.'/dataTables/media/js/jquery.dataTables.min.js')
			//->appendFile(LIBRARY_PATH.'/dataTables/extras/TableTools/media/js/ZeroClipboard.js')
			//->appendFile(LIBRARY_PATH.'/dataTables/extras/TableTools/media/js/TableTools.js')
			->appendFile(LIBRARY_PATH.'/dataTables/extras/TableTools/media/js/TableTools.min.js')
			->appendFile(LIBRARY_PATH.'/dataTables/extras/ColVis/media/js/ColVis.min.js')
			// fonctions personnelles
			->appendFile(SCRIPTS_PATH.'/projet/dataTable/main.js');
	}

	protected function appendCSS() {
		$this->view->headLink()->appendStylesheet(LIBRARY_PATH.'/dataTables/media/css/jquery.dataTables.css')
			->appendStylesheet(LIBRARY_PATH.'/dataTables/extras/TableTools/media/css/TableTools.css')
#			->appendStylesheet(LIBRARY_PATH.'/dataTables/extras/TableTools/media/css/TableTools_JUI.css')
			->appendStylesheet(LIBRARY_PATH.'/dataTables/extras/ColVis/media/css/ColVis.css')
			->appendStylesheet(STYLES_PATH.'/projet/dataTable/table.css')
			->appendStylesheet(STYLES_PATH.'/projet/dataTable/contour.css');//ajout du css de personnalisation
	}


}
