<?php

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', array(
	'text' => 'Popup content',
	'href' => "#elgg-popup-test",
	'rel' => 'popup',
));

echo $link;

echo elgg_view_module('popup', 'Popup Test', $ipsum, array(
	'id' => 'elgg-popup-test',
	'class' => 'hidden theme-sandbox-content-thin',
));

$button = elgg_format_element('button', array(
	'class' => 'elgg-popup elgg-button elgg-button-submit mll',
	'data-href' => "#elgg-popup-test2",
	'data-position' => json_encode(array(
		'my' => 'left top',
		'at' => 'left bottom',
	)),
), 'Load content in a popup');

echo $button;

echo elgg_format_element('div', array(
	'id' => 'elgg-popup-test2',
	'class' => 'hidden theme-sandbox-content-thin elgg-module-popup',
), '');

elgg_require_js('theme_sandbox/javascript/popup');