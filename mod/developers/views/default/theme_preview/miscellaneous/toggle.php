<?php

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', array(
	'text' => 'Toggle content',
	'href' => "#elgg-toggle-test",
	'rel' => 'toggle'
));

echo $link;
echo elgg_view_module('featured', 'Toggle Test', $ipsum, array(
	'id' => 'elgg-toggle-test',
	'class' => 'hidden clearfix developers-content-thin',
));