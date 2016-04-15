<?php
/**
 * Provides CSS for colorbox and is inlined in elgg.css
 *
 * @todo 3.0 move the CSS into this view, and delete all "lightbox" views
 */

echo elgg_view('lightbox/elgg-colorbox-theme/colorbox.css', [
	'__core_usage' => true,
]);
