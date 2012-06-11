
$(function() {
	loadLibelle();
	$("#Categorie").change(loadLibelle);
	$("input[name=Date_Duree]").change(cacheChamp);
	$("form").on("change", '#Libelle', getUnite);
});

//changement des items
function loadLibelle() {
	$.ajax({
		url: '/fr/axstock-getlibellesbyidcategorie',
		data: { idCategorie : $('#Categorie').val() },
		success: function(sItem) {
			$('#result-Libelle').html(sItem);
			getUnite();
		}
	});
}

function cacheChamp() {
	if ($('[name=Date_Duree]:checked').val() == 1 ){
		$('#champ-Date_Per').show();
		$('#champ-Duree_Per').hide();
	} else {
		$('#champ-Date_Per').hide();
		$('#champ-Duree_Per').show();
	}
}

function getUnite() {
	$.ajax({
		url: '/fr/axproduits-getunitebyiditem',
		data: { idItem : $('#Libelle').val() },
		success: function(sUnite) {
			//$('#unite').remove();
			//$('#champ-Quantite').append(sUnite);
			$('#unite').html(sUnite);
		}
	});
}