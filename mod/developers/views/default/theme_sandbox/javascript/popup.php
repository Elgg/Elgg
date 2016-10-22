<?php

$ipsum = elgg_view('developers/ipsum');

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'anchor',
			'text' => 'Popup content',
			'href' => "#elgg-popup-test",
			'rel' => 'popup',
		],
		[
			'#type' => 'button',
			'class' => 'elgg-button elgg-button-submit',
			'rel' => 'popup',
			'data-href' => "#elgg-popup-test2",
			'data-position' => json_encode(array(
				'my' => 'left top',
				'at' => 'left bottom',
			)),
			'value' => 'Load content in a popup',
		],
		[
			'#type' => 'button',
			'class' => 'elgg-button elgg-button-submit',
			'rel' => 'popup',
			'data-ajax-href' => 'ajax/view/theme_sandbox/components/tabs/ajax',
			'data-ajax-reload' => true,
			'data-ajax-query' => json_encode([
				'content' => 'Hello, world',
			]),
			'data-ajax-target' => json_encode([
				'class' => 'theme-sandbox-content-thin elgg-module-popup',
			]),
			'data-position' => json_encode(array(
				'my' => 'left top',
				'at' => 'left bottom',
			)),
			'value' => 'Create new ajax popup',
		]
	],
]);

echo elgg_view_module('popup', 'Popup Test', $ipsum, array(
	'id' => 'elgg-popup-test',
	'class' => 'hidden theme-sandbox-content-thin',
));

echo elgg_format_element(array(
	'#tag_name' => 'div',
	'id' => 'elgg-popup-test2',
	'class' => 'hidden theme-sandbox-content-thin elgg-module-popup',
));

elgg_require_js('theme_sandbox/javascript/popup');
