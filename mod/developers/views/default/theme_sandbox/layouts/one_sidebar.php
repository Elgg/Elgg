<?php

$ipsum = elgg_view('developers/ipsum');

$page_url = current_page_url();

echo elgg_view_layout('default', [
	'sidebar' => "<b>Primary sidebar</b>: $ipsum",
	'title' => 'Layout with one sidebar',
	'content' => "<b>Layout content</b>: $ipsum",
	'footer' => "<b>Layout footer</b>: $ipsum",
	'filter' => [
		'tab1' => [
			'text' => 'Tab 1',
			'href' => elgg_http_add_url_query_elements($page_url, ['filter' => 'tab1']),
		],
		'tab2' => [
			'text' => 'Tab 2',
			'href' => elgg_http_add_url_query_elements($page_url, ['filter' => 'tab2']),
		],
	],
	'filter_value' => get_input('filter', 'tab1'),
]);
