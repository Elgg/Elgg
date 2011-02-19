elgg.provide('elgg.messageboard');

elgg.messageboard.init = function() {
	$('form.elgg-messageboard input[type=submit]').live('click', elgg.messageboard.submit);
}

elgg.messageboard.submit = function(e) {
	var form = $(this).parents('form');
	var data = form.serialize();

	elgg.action('messageboard/add', {
		data: data,
		success: function(json) {
			form.parent().find('#messageboard_wrapper').prepend(json.output.post);
			form.find('textarea').val('');
		}
	});

	e.preventDefault();
}



elgg.register_event_handler('init', 'system', elgg.messageboard.init);