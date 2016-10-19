<?php

$page = elgg_extract('page', $vars);
elgg_load_css('dev.theme_sandbox');

elgg_require_js('elgg/dev/theme_sandbox');

$title = elgg_echo("theme_sandbox:{$page}");
$body =  elgg_view("theme_sandbox/{$page}");

$layout_options = [
	'title' => $title,
	'content' => $body,
	'sidebar' => 'Sidebar',
];

$layout = get_input('layout', 'one_sidebar');
switch ($layout) {
	case 'one_column':
		$layout_options['sidebar'] = false;
		break;
	case 'two_sidebar':
		$layout_options['sidebar_alt'] = 'Alt Sidebar';
		break;
}

$content = elgg_view_layout('default', $layout_options);

echo elgg_view_page($title, $content);
