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

elgg.provide('elgg.ui.lightbox');

elgg.ui.lightbox.init = function() {
	$(".elgg-lightbox").colorbox();
	$(".elgg-lightbox-photo").colorbox({photo: true});
	
	var n = 0;
	
	$(".elgg-lightbox-gallery").each(function() {
		$(this).find(".elgg-lightbox, .elgg-lightbox-photo")
		.addClass("elgg-lightbox-" + n)
		.colorbox({
			rel: "elgg-lightbox-" + n++,
		});
	});
}

elgg.ui.lightbox.close = function() {
	$.colorbox.close();
}

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox.init);

<?php

$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/colorbox/colorbox/jquery.colorbox-min.js";
include $js_path;
