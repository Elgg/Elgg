<?php
/**
 * Likes JavaScript extension for elgg.js
 */
?>
//<script>

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
elgg.ui.likesPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-likes')) {
		options.my = 'right bottom';
		options.at = 'left top';
		return options;
	}
	return null;
};

/**
 * Handles Elgg ajax response object
 *
 * @param {Object} data Data from ajax
 */
elgg.ui.likesSuccess = function(data) {
	var func_name = data.output.num_likes > 0 ? 'removeClass' : 'addClass';
	$(data.output.selector).text(data.output.text)[func_name]('hidden');
};

elgg.register_hook_handler('init', 'system', function () {
	// prevent focus from sitting on other icon
	$(document).on('click', '.elgg-menu-item-likes a, .elgg-menu-item-unlike a', function () {
		$('.elgg-menu-item-likes a, .elgg-menu-item-unlike a').blur();
	});
});

elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.likesPopupHandler);
elgg.ui.registerTogglableMenuItems('likes', 'unlike', elgg.ui.likesSuccess, elgg.ui.likesSuccess);
