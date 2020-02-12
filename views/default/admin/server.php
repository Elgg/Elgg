<?php
/**
 * Server information
 */

$tabs = [
	[
		'text' => elgg_echo('admin:server:label:requirements'),
		'content' => elgg_view('admin/server/requirements'),
		'selected' => true,
	],
	[
		'text' => elgg_echo('admin:server:label:web_server'),
		'content' => elgg_view('admin/server/web_server'),
	],
	[
		'text' => elgg_echo('admin:server:label:php'),
		'content' => elgg_view('admin/server/php'),
	],
];

// Show phpinfo page
if (elgg_get_config('allow_phpinfo') === true) {
	$tabs[] = [
		'text' => elgg_echo('admin:server:label:phpinfo'),
		'content' => elgg_view('output/iframe', [
			'src' => elgg_generate_url('phpinfo'),
			'width' => '100%',
			'height' => '2000px',
		]),
	];
}

elgg_register_menu_item('title', [
	'name' => 'diagnostics',
	'text' => elgg_echo('diagnostics:report'),
	'icon' => 'download',
	'href' => elgg_generate_action_url('diagnostics/download'),
	'class' => 'elgg-button elgg-button-action',
]);

echo elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);
