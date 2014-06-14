<?php
/**
 * Likes JavaScript extension for elgg.js
 */
?>
elgg.deprecated_notice('Use of elgg.likes is deprecated in favor of the elgg/likes AMD module', '1.9');
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

elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.likesPopupHandler);
elgg.ui.registerTogglableMenuItems('like', 'unlike');