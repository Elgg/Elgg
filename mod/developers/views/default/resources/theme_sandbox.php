<?php

$page = elgg_extract('page', $vars);
elgg_load_css('dev.theme_sandbox');

$pages = [
	'buttons',
	'components',
	'forms',
	'grid',
	'icons',
	'javascript',
	'layouts',
	'modules',
	'navigation',
	'typography',
];

foreach ($pages as $page_name) {
	elgg_register_menu_item('theme_sandbox', [
		'name' => $page_name,
		'text' => elgg_echo("theme_sandbox:$page_name"),
		'href' => "theme_sandbox/$page_name",
	]);
}

elgg_require_js('elgg/dev/theme_sandbox');

$title = elgg_echo("theme_sandbox:{$page}");
$body =  elgg_view("theme_sandbox/{$page}");

$layout = elgg_view_layout('theme_sandbox', [
	'title' => $title,
	'content' => $body,
]);

echo elgg_view_page("Theme Sandbox : $title", $layout, 'theme_sandbox');
