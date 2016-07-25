/**
 * This file is registered as a elgg/ui.river.js view in engine/views.php
 * and loaded as elgg.ui.river js via simplecache URL.
 * Since deprecation in 2.2, core does not use this file any more. Plugins may still
 * be loading it from core/river/filter view via elgg_load_js("elgg.ui.river")
 * @deprecated 2.2
 */
require(['elgg'], function (elgg) {
	elgg.provide('elgg.ui.river');

	elgg.ui.river.init = function () {
		elgg.deprecated_notice('ui.river.js library has been deprecated. Update core/river/filter and forms/comment/save views to require component AMD modules instead.', '2.2')
		require(['core/river/filter', 'forms/comment/save']);
	};

	elgg.register_hook_handler('init', 'system', elgg.ui.river.init);
});