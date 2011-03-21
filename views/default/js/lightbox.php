<?php
/**
 * Elgg lightbox
 *
 * Usage
 * Apply the class elgg-lightbox to links.
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
	$(".elgg-lightbox").fancybox();
}

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox_init);

<?php

$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/fancybox/jquery.fancybox-1.3.4.pack.js";
include $js_path;
