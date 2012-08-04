<?php
/**
 * Elgg lightbox
 *
 * Usage
 * Apply the class elgg-lightbox to links.
 *
 * Advanced Usage
 * Elgg is distributed with the Colorbox jQuery library. Please go to
 * http://www.jacklmoore.com/colorbox for more information on the options of this lightbox.
 *
 * Overriding
 * In a plugin, override this view and override the registration for the
 * lightbox JavaScript and CSS (@see elgg_views_boot()).
 *
 * @todo add support for passing options: $('#myplugin-lightbox').elgg.ui.lightbox(options);
 */

?>

/**
 * Lightbox initialization
 */
elgg.ui.lightbox_init = function() {
	$.extend($.colorbox.settings, {
		current: elgg.echo('js:lightbox:current', ['{current}', '{total}']),
		previous: elgg.echo('previous'),
		next: elgg.echo('next'),
		close: elgg.echo('close'),
		xhrError: elgg.echo('error:default'),
		imgError: elgg.echo('error:default'),
	});

	$(".elgg-lightbox").colorbox();
}

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox_init);

<?php

$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/colorbox/colorbox/jquery.colorbox-min.js";
include $js_path;
