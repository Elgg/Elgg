<?php

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', array(
	'text' => 'Toggle without animation',
	'href' => "#elgg-toggle-test",
	'rel' => 'toggle',
));

$link2 = elgg_view('output/url', array(
	'text' => 'Toggle with animation',
	'href' => "#elgg-toggle-test",
	'rel' => 'toggle',
	'class' => 'mlm',
	'data-animation' => json_encode([
		'open' => 'slideInDown',
		'close' => 'slideOutUp',
	]),
));

echo elgg_format_element('div', [], $link . $link2);

echo elgg_view_module('featured', 'Toggled module', $ipsum, array(
	'id' => 'elgg-toggle-test',
	'class' => 'hidden theme-sandbox-content-thin mtm',
));