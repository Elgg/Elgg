/**
 * Messageboard module
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	var MB = {
		init: function() {
			var form = $('form[name=elgg-messageboard]');
			form.on('click', '[type=submit]', MB.submit);

			// remove the default binding for confirmation since we're doing extra stuff.
			// @todo remove if we add a hook to the requires confirmation callback
			form.parent().on('click', '.elgg-menu-item-delete > a', function(event) {
				// double whammy for in case the load order changes.
				$(this).unbind('click', elgg.ui.requiresConfirmation).removeAttr('data-confirm');

				MB.deletePost(this);
				event.preventDefault();
			});
		},

		submit: function(e) {
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
					}
					form.find('textarea').val('');
				}
			});

			e.preventDefault();
		},

		deletePost: function(elem) {
			var $link = $(elem);
			var confirmText = $link.attr('title') || elgg.echo('question:areyousure');

			if (confirm(confirmText)) {
				elgg.action($link.attr('href'), {
					success: function() {
						var item = $link.closest('.elgg-item');
						item.remove();
					}
				});
			}
		}
	};

	elgg.register_hook_handler('init', 'system', MB.init);
});
