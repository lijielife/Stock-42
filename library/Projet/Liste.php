<?php
/** @brief	Affichage d'entités en liste avec possibilités d'actions dans une dernière colonne
 *
 * @see Projet_View_Helper_Datatable
 *
 * @category   Projet
 * @author     amboise.lafont
 */
class Projet_Liste {
	protected $view;

	/** @brief	libellé par défaut de la colonne d'action
	 */
	const COLONNE_EDITION_NOM = 'editer.nom';

	/** @brief	libellé à afficher pour la colonne d'action
	 */
	protected $colonneEdition = self::COLONNE_EDITION_NOM;
	/** @brief	nom des colonnes (sauf la colonne d'action)
	 */
	protected $aCols;
	/** @brief	liste des actions
	 *
	 * format : [ [ 'url' => url, 'html' => html, 'class' => classe ] ]
	 *
	 * @see addAction pour la signification
	 */
	protected $aActions = array();
	/** @brief	url de la liste à charger en ajax
	 */
	protected $sUrlListe;
	/** @brief	url du formulaire d'ajout
	 */
	protected $sUrlAjout = '';
	/** @brief	id du table
	 */
	protected $id;
	/** @brief	code js à exécuter après initialisation de la table
	 */
	protected $sJs = '';

	/** @brief	classes pour les colonnes */
	protected $aClasses = array();

	/** @brief	propriété aoColumnDefs du datatable */
	protected $sColumnDefs = '[]';



	//classe à accorder avec le js
	const CLASSE_SUPPR = 'helper-suppr';
	const CLASSE_EDIT  = 'helper-edit';
	const HTML_SUPPR = '<img src="/gabarit/images/template/icons/fugue/cross-circle.png"/>';
	const HTML_EDIT = '<img src="/gabarit/images/template/icons/fugue/pencil.png"/>';


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
	public function __construct($oView, array $aCols, $sUrlListe, $id) {
		$this->view = $oView;
		$this->aCols = $aCols;
		$this->sUrlListe = $sUrlListe;
		$this->id = $id;
	}

	/** @brief	définit l'url d'ajout
	 * le bouton d'ajout est placé dans le placeHolder('bottom')
	 */
	public function setUrlAjout($sUrlAjout) {
		$this->sUrlAjout = $sUrlAjout;
		return $this;
	}

	/** @brief	définit le nom de la colonne d'action
	 */
	public function setColAction($sNom) {
		$this->colonneEdition = $sNom;
		return $this;
	}

	/** @brief	Ajoute une action possible dans la colonne édition
	 * addAction('/url', 'code html', 'maclasse') donne un code html <a href="/url?id=5" class="maclasse">code html</a>
	 * dans la colonne d'action, avec 5 l'id de la ligne courante
	 */
	public function addAction($sUrl, $sHtml, $sClasse = '') {
		// on n'ajoute que si l'url est non nul
		$sUrl && $this->aActions[] = array('url' => $sUrl, 'html' => $sHtml, 'class' => $sClasse);
		return $this;
	}

	public function addActionSupprimer($sUrl) {
		return $this->addAction($sUrl, self::HTML_SUPPR, self::CLASSE_SUPPR);
	}

	public function addActionEditer($sUrl, $sHtml = self::HTML_EDIT) {
		return $this->addAction($sUrl, $sHtml, self::CLASSE_EDIT);
	}

	public function setClasses($aClasses) {
		$this->aClasses = $aClasses;
		return $this;
	}

	/** @brief	Ajoute du code js à exécuter après initialisation de la table
	 * La variable js table contient alors le datatable courant
	 */
	public function addJsInit($sJs) {
		$this->sJs .= $sJs;
		return $this;
	}

	/** @brief aoColumnsDef du datatable */
	public function setColumnDefs($sAoColumns) {
		$this->sColumnDefs = $sAoColumns;
		return $this;
	}

	/** @brief	Rendu en chaine de caractères
	 */
	public function render() {

		//Commenté car utile si on ne veut pas télécharger la liste illico
#		if (!$this->sUrlListe) {
#			// Ce cas n'est pas censé se produire
#			// information de déboggage
#			return 'Liste inaccessible';
#		}


		// utilisation du plugin jquery dataTable (cf public/gabarit/scripts/projet/dataTable.js pour createDataTable
		$sScript = '
$(document).ready(function() {'.$this->jsOnReady().'
	}
);';
		$this->view->headScript()->appendScript($sScript);

		// affichage de la colonne d'édition si et seulement si on peut modifier, ou supprimer.
		return $this->createTable();
	}


	/** @brief	Crée le squelette html du table
	 *
	 */
	protected function createTable() {
		$sHtml = '<table id="'.$this->id.'">
	<thead>
		<tr>';
		foreach ($this->aCols as $value) {
			$sHtml .= '<th>'.$this->view->translate($value).'</th>';
		}
		
		if ($this->aActions) {
			$sHtml .= '<th>'.$this->view->translate($this->colonneEdition).'</th>';
		}

		$sHtml .= '</tr>
	</thead>
	<tbody>
	</tbody>
	<tfoot>
	</tfoot>

</table>';

		return $sHtml;
	}


/*
	protected function ajouterBouton() {
		$oButt = new Symbol_Button("",$this->view->translate("form.action.add"),"");
		return $oButt->render();
	}*/

	public function __toString() {
		return $this->render();
	}

	protected function jsOnReady() {
		$saAnchor = Zend_Json::encode($this->aActions);
		$sScript = '
		var table = createDataTable($("#'.$this->id.'"), "'.$this->sUrlListe.'", '.$saAnchor.', '.Zend_Json::encode($this->aClasses).', '.$this->sColumnDefs.', "'. $this->sUrlAjout. '");';
		

		//commenté car fait bouton ajouter en js
#		if ($this->sUrlAjout) {
#			$this->view->placeholder('bottom')->set($this->ajouterBouton());
#			// on cible le bouton ajout
#			$sScript .= '$("footer button").click(function () { addForm(table, "'.$this->sUrlAjout.'",{}); });';
#		}

		return $sScript.$this->sJs;
	}
}
