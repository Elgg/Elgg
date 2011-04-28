//<script>
elgg.provide('elgg.messageboard');

elgg.messageboard.init = function() {
	var form = $('form[name=elgg-messageboard]');
	form.find('input[type=submit]').live('click', elgg.messageboard.submit);

	// remove the default binding for confirmation since we're doing extra stuff.
	// @todo remove if we add a hook to the requires confirmation callback
	form.parent().find('a.elgg-requires-confirmation')
		.click(elgg.messageboard.deletePost)

		// double whammy for in case the load order changes.
		.unbind('click', elgg.ui.requiresConfirmation)
		.removeClass('elgg-requires-confirmation');
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

elgg.register_hook_handler('init', 'system', elgg.messageboard.init);
