<?php
/**
 * Elgg lightbox
 *
 * Usage
 * Call elgg_load_js('lightbox') and elgg_load_css('lightbox') then
 * apply the class elgg-lightbox to links.
 *
 * Advanced Usage
 * Elgg is distributed with the Colorbox jQuery library. Please go to
 * http://www.jacklmoore.com/colorbox for more information on the options of this lightbox.
 *
 * You can add colorbox behavior to HTML added dynamically by re-calling:
 * elgg.ui.lightbox.init();
 *
 * You can change global options by overriding the js/lightbox/settings view.
 *
 * You may apply colorbox options to an individual .elgg-lightbox element
 * by setting the attribute data-colorbox-opts to a JSON settings object. You
 * can also set options in the elgg.ui.lightbox.open() method, but data
 * attributes will take precedence.
 *
 * Overriding
 * In a plugin, override this view and override the registration for the
 * lightbox JavaScript and CSS (@see elgg_views_boot()).
 */

?>
//<script>

elgg.provide('elgg.ui.lightbox');

<?php echo elgg_view('js/lightbox/settings'); ?>

elgg.ui.lightbox.init = function() {
	$.extend($.colorbox.settings, elgg.ui.lightbox.getSettings());
	elgg.ui.lightbox.open($(".elgg-lightbox"));
};

/**
 * Open a colorbox
 *
 * @param {Object} $element jQuery object containing colorbox openers
 * @param {Object} opts colorbox options. These are overridden by data-colorbox-opts options
 */
elgg.ui.lightbox.open = function ($element, opts) {
	if (!$.isPlainObject(opts)) {
		opts = {};
	}
	// Q: why not use "colorbox"? A: https://github.com/jackmoore/colorbox/issues/435
	var dataOpts = $element.data('colorboxOpts');
	if ($.isPlainObject(dataOpts)) {
		opts = $.extend(opts, dataOpts);
	}
	$element.colorbox(opts);
};

elgg.ui.lightbox.close = function() {
	$.colorbox.close();
};

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox.init);

<?php
$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/colorbox/jquery.colorbox-min.js";
readfile($js_path);
