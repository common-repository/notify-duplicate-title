jQuery(document).ready(function($){
	$('#title').on('change', function(){
		var post_id = $('#post_ID').val();
		var post_title = $('#title').val();
		var post_type = $('#post_type').val();

		var data = {
			action: 'duplicate_title_checker',
			post_id: post_id,
			post_title: post_title,
			post_type: post_type,
			security: NDT.nonce
		};

		$.ajax({
			url: NDT.endpoint,
			data: data,
			type: 'POST',
			cache: false,
			dataType: 'html'
		}).done(function(response){
			$('#message').remove();
			$('#poststuff').prepend(response);
		});
	});
});
