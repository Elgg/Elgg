<?php
/**
 * Elgg custom index page
 *
 * You can edit the content of this page with your own layout and style.
 * Whatever you put in this view will appear on the front page of your site.
 *
 */

$body = elgg_view_layout('default', [
	'content' => elgg_view('custom_index/content', $vars),
	'sidebar' => false,
]);

echo elgg_view_page('', $body);
