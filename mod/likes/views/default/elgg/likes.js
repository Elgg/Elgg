/**
 * Likes module
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var click = require('elgg/click');

	/**
	 * Repositions the likes popup
	 *
	 * @param {String} hook    'getOptions'
	 * @param {String} type    'ui.popup'
	 * @param {Object} params  An array of info about the target and source.
	 * @param {Object} options Options to pass to
	 *
	 * @return {Object}
	 */
	function popupHandler(hook, type, params, options) {
		if (params.target.hasClass('elgg-likes')) {
			options.my = 'right bottom';
			options.at = 'left top';
			return options;
		}
		return null;
	}

	elgg.register_hook_handler('getOptions', 'ui.popup', popupHandler);

	function setupHandlers(nameA, nameB) {
		$(document).on('click', '.elgg-menu-item-' + nameA + ' a', function() {
			var $menu = $(this).closest('.elgg-menu');

			// Be optimistic about success
			elgg.ui.toggleMenuItems($menu, nameB, nameA);

			$menu.find('.elgg-menu-item-' + nameB + ' a').blur();

			// Send the ajax request
			elgg.action($(this).attr('href'), {
				success: function(data) {
					if (data.system_messages.error.length) {
						// Something went wrong, so undo the optimistic changes
						elgg.ui.toggleMenuItems($menu, nameA, nameB);
					}

					var func_name = data.output.num_likes > 0 ? 'removeClass' : 'addClass';
					$(data.output.selector).text(data.output.text)[func_name]('hidden');
				},
				error: function() {
					// Something went wrong, so undo the optimistic changes
					elgg.ui.toggleMenuItems($menu, nameA, nameB);
				}
			});

			// Don't want to actually click the link
			return false;
		});
	}

	// temp hack to emulate slow loading
	setTimeout(function () {
		setupHandlers('likes', 'unlike');
		setupHandlers('unlike', 'likes');
		click.replay('likes');
	}, 5000);
});
