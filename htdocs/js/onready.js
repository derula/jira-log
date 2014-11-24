$( document ).ready(function() {

	$.ajaxSetup(
		{
			headers: {"X-Is-Ajax-Call": "yes"},
			beforeSend: function(xhr){xhr.setRequestHeader('X-Is-Ajax-Call', 'yes');}
		}
	);

	$('#testConnection').click(function() {
		var data = {};

		$.ajax({
		  url: "/test",
		  data: data,
		  dataType: 'JSON',
		  type: 'POST',
		  success: function (jsonResponse) {
			if (jsonResponse.error) {
				$('.errorBox').html(jsonResponse.error);
			}
		  }
		})
		;
	});

});