<?php
/**
 * Likes JavaScript extension for elgg.js
 */
?>

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
	if (params.target.hasClass('elgg-likes-list')) {
		options.my = 'right bottom';
		options.at = 'left top';
		return options;
	}
	return null;
};

elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.likesPopupHandler);