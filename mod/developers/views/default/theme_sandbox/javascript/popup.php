<?php

$ipsum = elgg_view('developers/ipsum');

echo elgg_view('output/url', [
	'text' => 'Popup content',
	'href' => "#elgg-popup-test",
	'rel' => 'popup',
]);

echo elgg_view_module('popup', 'Popup Test', $ipsum, [
	'id' => 'elgg-popup-test',
	'class' => 'hidden theme-sandbox-content-thin',
]);


$button = elgg_format_element([
	'#tag_name' => 'button',
	'class' => 'elgg-button elgg-button-submit mll',
	'rel' => 'popup',
	'data-href' => "#elgg-popup-test2",
	'data-position' => json_encode([
		'my' => 'left top',
		'at' => 'left bottom',
	]),
	'#text' => 'Load content in a popup',
]);

echo $button;

echo elgg_format_element([
	'#tag_name' => 'div',
	'id' => 'elgg-popup-test2',
	'class' => 'hidden theme-sandbox-content-thin elgg-module-popup',
]);

elgg_require_js('theme_sandbox/javascript/popup');
