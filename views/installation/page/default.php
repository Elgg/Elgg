<?php
/**
 * Elgg install pageshell
 */

$params = [
	'head' => elgg_view('page/elements/head', $vars),
	'body' => elgg_view('page/elements/body', $vars),
];

echo elgg_view('page/elements/html', $params);
