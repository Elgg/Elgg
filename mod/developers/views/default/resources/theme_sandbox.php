<?php

$page = elgg_extract('page', $vars);
if (!elgg_view_exists("theme_sandbox/{$page}")) {
	throw new \Elgg\EntityNotFoundException();
}

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

$menu = elgg_view_menu('theme_sandbox', [
	'class' => 'elgg-menu-page',
]);

$layout = elgg_view_layout('default', [
	'title' => $title,
	'content' => $body,
	'sidebar' => $menu,
]);

echo elgg_view_page("Theme Sandbox : $title", $layout);
