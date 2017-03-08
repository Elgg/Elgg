/**
 * Likes module
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');

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
	
	/**
	 * Updates the likes_count menu item
	 *
	 * @param {String} hook    'toggle'
	 * @param {String} type    'menu_item'
	 * @param {Object} params  An array of info about the toggled menu items.
	 * @param {Object} options Options to pass to
	 *
	 * @return void
	 */
	function likesToggle(hook, type, params, options) {
		if (!params.itemClicked.hasClass('elgg-menu-item-likes') && 
				!params.itemClicked.hasClass('elgg-menu-item-unlike') ) {
			return;
		}
		
		var $count_item = params.menu.find('.elgg-menu-item-likes-count');
		var func_name = params.data.output.num_likes > 0 ? 'removeClass' : 'addClass';
		$count_item.text(params.data.output.text)[func_name]('hidden');
	}

	elgg.register_hook_handler('getOptions', 'ui.popup', popupHandler);
	elgg.register_hook_handler('toggle', 'menu_item', likesToggle);
});
