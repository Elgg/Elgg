elgg.provide('elgg.messageboard');

elgg.messageboard.init = function() {
	var form = $('form[name=elgg-messageboard]');
	form.find('input[type=submit]').live('click', elgg.messageboard.submit);

	// can't undelete because of init load order
	form.parent().find('a.elgg-requires-confirmation').removeClass('elgg-requires-confirmation');
	// delegate() instead of live() because live() has to be at the top level of chains...can't use parent().

	// delete is a little-known operator in JS. IE loses its mind if you name a method that.
	form.parent().delegate('.delete-button a', 'click', elgg.messageboard.deletePost);
}

elgg.messageboard.submit = function(e) {
	var form = $(this).parents('form');
	var data = form.serialize();

	elgg.action('messageboard/add', {
		data: data,
		success: function(json) {
			// the action always returns the full ul and li wrapped annotation.
			var ul = form.next('ul.elgg-annotation-list');

			if (ul.length < 1) {
				form.parent().append(json.output);
			} else {
				ul.prepend($(json.output).find('li:first'));
			};
			form.find('textarea').val('');
		}
	});

	e.preventDefault();
}

elgg.messageboard.deletePost = function(e) {
	var link = $(this);
	var confirmText = link.attr('title') || elgg.echo('question:areyousure');

	if (confirm(confirmText)) {
		elgg.action($(this).attr('href'), {
			success: function() {
				$(link).closest('li').remove();
			}
		});
	}

	e.preventDefault();
}


elgg.register_event_handler('init', 'system', elgg.messageboard.init);