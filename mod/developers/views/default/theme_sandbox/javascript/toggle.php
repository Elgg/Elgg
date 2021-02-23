<?php

$ipsum = elgg_view('developers/ipsum');

echo elgg_view('output/url', [
	'text' => 'Toggle content',
	'href' => "#elgg-toggle-test",
	'rel' => 'toggle',
]);

echo elgg_view_module('featured', 'Toggle Test', $ipsum, [
	'id' => 'elgg-toggle-test',
	'class' => 'hidden theme-sandbox-content-thin mtm',
]);
