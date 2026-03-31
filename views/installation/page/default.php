<?php
/**
 * Elgg install pageshell
 */

echo elgg_view('page/elements/html', [
	'html_attrs' => [
		'data-color-scheme' => 'default', // always use default color scheme in installer
	],
	'head' => elgg_view('page/elements/head', $vars),
	'body' => elgg_view('page/elements/body', $vars),
]);
