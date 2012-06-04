
$(function() {
	$("#Categorie").change(loadLibelle);
	loadLibelle();
});

//changement des items
function loadLibelle() {
	$.ajax({
		url: '/fr/axstock-getlibellesbyidcategorie',
		data: { idCategorie : $('#Categorie').val() },
		success: function(sItem) {
			$('#result-Libelle').html(sItem);
		}
	});
}
