define(function(elgg) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	function submit(e) {
		var form = $(this).parents('form');
		var data = form.serialize();

		elgg.action('messageboard/add', {
			data: data,
			success: function(json) {
				// the action always returns the full ul and li wrapped annotation.
				var ul = form.next('ul.elgg-list-annotation');

				if (ul.length < 1) {
					form.parent().append(json.output);
				} else {
					ul.prepend($(json.output).find('li:first'));
				};
				form.find('textarea').val('');
			}
		});

		e.preventDefault();
	};

	function deletePost(e) {
		var link = $(this);
		var confirmText = link.attr('title') || elgg.echo('question:areyousure');

		if (confirm(confirmText)) {
			elgg.action($(this).attr('href'), {
				success: function() {
					var item = $(link).closest('.elgg-item');
					item.remove();
				}
			});
		}

		e.preventDefault();
	};

	elgg.register_hook_handler('init', 'system', 	function init() {
		var form = $('form[name=elgg-messageboard]');
		form.find('input[type=submit]').live('click', submit);

		// remove the default binding for confirmation since we're doing extra stuff.
		// @todo remove if we add a hook to the requires confirmation callback
		form.parent().find('a.elgg-requires-confirmation')
			.click(deletePost)

			// double whammy for in case the load order changes.
			.unbind('click', elgg.ui.requiresConfirmation)
			.removeClass('elgg-requires-confirmation');
	});
	
	return {
		init: init,
		submit: submit,
		deletePost: deletePost
	};
});