$(function () {
	$('#img').change(function () {
		$.each(this.files, function(i, f) {
			$('#uplist').append($('<li>').text(f.name));
		});
	});
});