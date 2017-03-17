<?php

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', [
	'text' => 'Toggle content',
	'href' => "#elgg-toggle-test",
	'rel' => 'toggle',
]);

echo $link;
echo elgg_view_module('featured', 'Toggle Test', $ipsum, [
	'id' => 'elgg-toggle-test',
	'class' => 'hidden theme-sandbox-content-thin mtm',
]);
