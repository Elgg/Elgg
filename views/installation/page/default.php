<?php
/**
 * Elgg install pageshell
 */

echo elgg_view('page/elements/html', [
	'head' => elgg_view('page/elements/head', $vars),
	'body' => elgg_view('page/elements/body', $vars),
]);
