/** @brief utilitaire pour la création de dataTable en tandem avec le helper Datatable
 * 
 * @author amboise.lafont
 */
 
// ces variables contiendraient d'éventuelles traductions
if (oLanguageDataTable == undefined) { var oLanguageDataTable = {}; }
//if (sMessageSucces == undefined) { var sMessageSucces = 'Enregistrement supprimé avec succès'; }
if (sMessageConfirm == undefined) { var sMessageConfirm = 'Etes-vous sûr de vouloir supprimer cet enregistrement ?'; }
if (sOui == undefined) { var sOui = 'Oui'; }
if (sNon == undefined) { var sNon = 'Non'; }
if (sAjouter == undefined) { var sAjouter = 'Ajouter'; }
if (sConfirmTitre == undefined) {var sConfirmTitre = 'Confirmation';}


/** @brief transforme un élément table ciblé en jQuery en DataTable
 *
 * Les donnés du tableau sont chargés en ajax, le formulaire d'édition chargé en ajax
 * est placé dans la modale.
 *
 * @code
 * createDataTable($("table"), '/ajax/liste', [ { url:'/ajax/formulaire', html:'editer', class='edit' } ]);
 * @endcode
 *
 * L'url /ajax/liste doit alors fournir les donnés au format json suivant :
 * @code
 * { "aaData" : [ [Col1, Col2, Col3], [Col1, Col2, Col3] ] }
 * @endcode
 *
 * La dernière colonne doit fournir l'id : elle est utilisée pour la colonne éditer
 * le formulaire est alors appelé avec le paramètre id correspondant
 *
 * L'élément html table ciblé doit fournir l'entête thead avec les noms de colonnes
 * y compris la colonne d'édition si elle a lieu d'être.
 *
 * @param element élément jQuery du dom à transformer en DataTable
 * @param urlliste url permettant de récuperer en ajax les donnés du tableau
 * @param aAnchors liste des actions à placer dans la colonne d'action
 * @param aClasses tableau associatif des numéros de colonnes associés à leur classe css voulue
 * @param aoColumnDefs propriétés spécifiques pour certaines colonnes (cf datatables.net)
 * @return le dataTable nouvellement créé
 */
function createDataTable(element, urlliste, aAnchors, aClasses, aoColumnDefs, urlajout) {
	//boutons de tabletools
	var aButtons = [];

	// bouton ajouter si demandé
	if (urlajout) {
		aButtons.push(
			{
				"sExtends" : "text",
				//"sButtonText" : "<span class='plus'></span>" + sAjouter,
				"sButtonText" : "<img src='/gabarit/images/template/icons/fugue/plus-circle.png' />" + sAjouter,
				"sButtonClass" : "ajouter",
				"fnClick" : function () { addForm(table, urlajout, {}); }
			});
	}

	// colonnes à exporter
	//var mColumns = "visible";
	//finalement on exporte toutes les colonnes sauf la colonne d'édition
	var mColumns = "all";


	for (idx in aClasses) {
		aoColumnDefs.push( { "aTargets": [Number(idx)],
			"sClass": aClasses[idx]
		});
	}



	// variable qui contiendra le dataTable
	var table;

	// Si on des actions, il y a une colonne d'action
	if (aAnchors.length) {
	
		var exportLength = element.find('th').get().length - 1;
		// export des premières colonnes seulement
		mColumns = new Array(exportLength);
		for (var i = 0; i < exportLength; i++) {
			mColumns[i] = i;
		}
		

		/*
		 * on cible la dernière colonne avec aTargets
		 * le paramètre fnRender permet de spécifier comment
		 * rendre cette colonne (cf paramètre aoColumnDefs de dataTable)
		 */
		aoColumnDefs.push( { aTargets: [-1], 
			"fnRender": function (o, val) {
						// val contient l'id (la dernière colonne de la ligne courante chargé en ajax)
						var boutons='';

						for (var i=0; i < aAnchors.length; i++) {
							boutons += "<a class='" + aAnchors[i].class + "' href='" + aAnchors[i].url + '?id='+val+"'>" + aAnchors[i].html + '</a>';
						}

						return boutons;
				},
				//"sWidth":"5em",
				"bSortable":false
			}
		);

		// lorsqu'on clique sur le lien de la colonne édition, on charge le formulaire
		element.on("click", "tbody a.helper-edit", function(e) {
				e.preventDefault();
				// Chargement du formulaire en ajax
				addForm(table, this.href, {});
			}
		);
		// lorsqu'on clique sur le lien de la colonne edition sur le bouton suppression, on supprime
		element.on("click","tbody a.helper-suppr", function(e) {
			e.preventDefault();
			// Suppression de l'entrée en base
			deleteEntree(table, $(this));
		});
	}

	aButtons = aButtons.concat([
			{//creation d\'une collection de bouton pour 
				//les differents type d\'export
				"sExtends" : "collection",
				"sButtonText" : "Exporter",
				"aButtons" : [
				{
					"sExtends" : "pdf",
					"mColumns" : mColumns,
					"sButtonText" : "En PDF"
				},
				{
					"sExtends" : "csv",
					"mColumns" : mColumns,
					"sButtonText" : "En CSV"
				},
				{
					"sExtends": "download",
					"mColumns" : mColumns,
					"sButtonText": "En ODS",
					"sUrl": "/export-ods"
				},
				{
					"sExtends" : "copy",
					"mColumns" : mColumns,
					"sButtonText" : "Dans le Presse-Papier",
					"fnComplete": function(nButton, oConfig, flash, text) {
						var
							lines = text.split('\n').length,
							len = this.s.dt.nTFoot === null ? lines : lines-2,
							plural = (len==1) ? "" : "s";
						//$('<div >'+len+' ligne'+plural+' copiée'+plural+' dans le presse-papier'+'</div>').appendTo($('body').dialog({modal:true}));
						alert( 'Copiez les lignes puis cliquez sur ok'+len+' ligne'+plural+' copiée'+plural+' dans le presse-papier' );
					}
				} ]
			},
			{
				"sExtends" 		: "print",
				"sButtonText" 	: "Imprimer",
				"sInfo"			: oLanguageDataTable.sInfoImpression
			}
	]);

	// comme annoncé, la création du dataTable
	table = element.dataTable({

		"sAjaxSource": urlliste,
		//"bServerSide" : true,

		// configuration éventuelle de la colonne d'édition
		"aoColumnDefs": aoColumnDefs,
		"oLanguage" : oLanguageDataTable,

		"bJQueryUI": true,
		"bPaginate": true,
		"bStateSave": true,
		"bRetrieve": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
		"sPaginationType": "full_numbers",

		//appel du plugin TableTools
		"sDom" : "<\"H\"fl>t<\"F\"rip>",
		"oColVis": {
		//			"activate": "mouseover",
			"buttonText": "Ajouter/Supprimer des colonnes",
			"sAlign": "left",
			"bRestore": true
		},
		// Options du plugin TableTools
		"oTableTools" : {
			//fichier flash de fonctionnement des export pdf pour tabletool
			"sSwfPath" : "/gabarit/library/dataTables/extras/TableTools/media/swf/copy_cvs_xls_pdf.swf",
			//creation des boutons presents sur tableau
			"aButtons" : aButtons
		}
	});
	return table;
}



/** @brief	ajoute un formulaire dans la modale
 *
 * @param table dataTable concerné (rechargement des donnés en ajax)
 * @param url url du formulaire à charger en ajax
 * @param data code html du formulaire à placer dans la modale
 */
function addForm(table, urlform, data) {

	/** @brief	permet de mettre à jour le formulaire chargé en ajax dans la modale
	 *
	 * @param data code html du formulaire à placer dans la modale
	 */
	function updateForm(data) {
		// on fout le code html dans la modale (cf Modal.js)
		setHtmlModal(data);


		/*
		 * le formulaire doit être transmis en ajax (pas de rechargement)
		 * lorsque le submit a été effectué, on récupère la réponse du serveur
		 * avec updateForm
		 * url: action du formulaire à utiliser
		 */
		$(".modale form").ajaxForm({
				success: function(data) {
					updateForm(data);
					
					// calcul de la page courante
					var oSettings = table.fnSettings();
					var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;

					// Rechargement des donnés du tableau en ajax
					// TODO: pas très efficace : il suffit de modifier la ligne courante modifiée
					table.fnReloadAjax();

					// on se remet sur la même page
					table.fnPageChange(page);
				},
				url: urlform
			}
		);
	}

	// Chargement du formulaire en ajax
	$.ajax({
		url: urlform,
		data: data,
		success: function(data) {
			// mise à jour du formulaire
			updateForm(data);
			// la modale apparaît à l'écran
			openModal();
		}
	});
}

/** @brief Supprime une entrée du tableau en ajax
 * @author francois.espinet
 */
function deleteEntree(table, element) {
	// liste des colonnes du datatable
	var aColonnes = table.fnSettings().aoColumns;
	var oLigne = element.closest('tr').find('td');
	// liste des cellules de la ligne courante
	var aLigne = oLigne.toArray();
	// résumé de l'item à supprimer
	var texte = '';
	
	// ignore la dernière colonne : celle de l'édition
	for (var i = 0; i < aColonnes.length - 1; i++) {
		// colonne : valeur
		texte += "<br/>" + aColonnes[i].sTitle + ' : ' + aLigne[i].innerHTML;
	}

	var oDialog = $("#dialog");
	oDialog.html(sMessageConfirm+texte)

	var oBoutons = {};
	
	oBoutons[sOui] = function() {
		oDialog.dialog("close");
		// Supression de l'élement en ajax
		$.ajax({
			url: element.attr("href"),
			success: function(data) {
				notify(data);
				table.fnReloadAjax();
			}
		});
	};

	oBoutons[sNon] = function() {oDialog.dialog("close");};


	//$(sMessageConfirm + texte + 'coucou').appendTo('body').dialog({buttons : oBoutons, modal: true, title : sConfirmTitre});
	oDialog.dialog({buttons : oBoutons, modal: true, title : sConfirmTitre});

}

/**
 * @brief recharge les donnés du dataTable en ajax
 * copier coller du site dataTable dans api, plugin
 */
$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
	if ( typeof sNewSource != "undefined" && sNewSource != null )
	{
		oSettings.sAjaxSource = sNewSource;
	}
	this.oApi._fnProcessingDisplay( oSettings, true );
	var that = this;
	var iStart = oSettings._iDisplayStart;
	var aData = [];

	this.oApi._fnServerParams( oSettings, aData );

	oSettings.fnServerData( oSettings.sAjaxSource, aData, function(json) {
			/* Clear the old information from the table */
			that.oApi._fnClearTable( oSettings );

			/* Got the data - add it to the table */
			var aData =  (oSettings.sAjaxDataProp !== "") ?
			that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

			for ( var i=0 ; i<aData.length ; i++ )
			{
			that.oApi._fnAddData( oSettings, aData[i] );
			}

			oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
			that.fnDraw();

			if ( typeof bStandingRedraw != "undefined" && bStandingRedraw === true )
			{
			oSettings._iDisplayStart = iStart;
			that.fnDraw( false );
			}

			that.oApi._fnProcessingDisplay( oSettings, false );

			/* Callback user function - for event handlers etc */
			if ( typeof fnCallback == "function" && fnCallback != null )
			{
				fnCallback( oSettings );
			}
	}, oSettings );
}

/* *************************************************************************************
 *
 *
 * Fonctions copiés collés du site datatables.net pour l'export par téléchargement
 * adapté par amboise.lafont : fusion avec ajax
 *
 *
 ***************************************************************************************/
TableTools.BUTTONS.download = {
    "sAction": "text",
    "sFieldBoundary": "",
    "sFieldSeperator": "\t",
    "sNewLine": "\n",
    "sToolTip": "",
    "sButtonClass": "DTTT_button_text",
    "sButtonClassHover": "DTTT_button_text_hover",
    "sButtonText": "Download",
    "mColumns": "all",
    "bHeader": true,
    "bFooter": true,
    "sDiv": "",
    "fnMouseover": null,
    "fnMouseout": null,
	"fnClick": function( nButton, oConfig ) {
		var oData = {tableData: this.fnGetTableData(oConfig)};
		//alert(oData.tableData);
		download(oConfig.sUrl, oData);
	},
    "fnSelect": null,
    "fnComplete": null,
    "fnInit": null
};
 
// Downloaded from http://filamentgroup.com/lab/jquery_plugin_for_requesting_ajax_like_file_downloads/
// adapté par amboise.lafont
function download (url, data){
	var inputs = '';
	for (var name in data) {
		inputs+='<input type="hidden" name="'+ name +'" value="'+ data[name] +'" />'; 
//		alert(data[name]);
	}

	//send request
	$('<form action="'+ url +'" method="post">'+inputs+'</form>').appendTo('body').submit().remove();
};


	


