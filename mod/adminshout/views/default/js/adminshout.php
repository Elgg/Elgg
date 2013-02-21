elgg.provide('elgg.admin.shout');

elgg.admin.shout.send = function(request) {
	elgg.action('adminshout/send', {
		data: request,
		success: function(response) {
			$('#progressbar').progressbar('value', response.output.sent * 100 / response.output.total);
			if (response.output.sent < response.output.total) {
				request.offset += request.limit; 
				elgg.admin.shout.send(request);
			}
		}
	});
};

elgg.admin.shout.progressBar = function() {
	$('#progressbar').progressbar({
		value: false,
		change: function() {
			$('.progress-label', this).text( $(this).progressbar( "value" ) + "%" );
		},
		complete: function() {
			$('.progress-label', this).text( elgg.echo('adminshout:success') );
		}
	}).fadeIn();
}

elgg.admin.shout.init = function() {
	$('.elgg-form-adminshout-send').submit(function(e) {
		var subject = $(this).find('[name="subject"]').val();
		var message = $(this).find('[name="message"]').val();
		
		if (!subject || !message) {
			elgg.register_error(elgg.echo('adminshout:inputs'));
			return false;
		}
		
		elgg.admin.shout.send({
			subject: subject,
			message: message,
			offset: 0,
			limit: 10,
		});
		$(this).fadeOut();
		elgg.admin.shout.progressBar();
		e.preventDefault();
	});
};

elgg.register_hook_handler('init', 'system', elgg.admin.shout.init);