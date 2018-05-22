<?php

/**
 * Renders a standard HTML page shell
 *
 * @uses $vars['section']       An array of page sections to render
 * @uses $vars['html_attrs']    Attributes of the <html> tag
 * @uses $vars['head']          Parameters for the <head> element
 * @uses $vars['body_attrs']    Attributes of the <body> tag
 * @uses $vars['page_attrs']    Attributes of the .elgg-page container
 * @uses $vars['title']         Title of the page
 * @uses $vars['body']          The main content of the page
 * @uses $vars['sysmessages']   A 2d array of various message registers, passed from system_messages()
 */

$sections = elgg_extract('sections', $vars);

if (empty($sections)) {
	// render content before head so that JavaScript and CSS can be loaded. See #4032
	$sections = [
		'messages' => elgg_view('page/elements/messages', [
			'object' => elgg_extract('sysmessages', $vars),
		]),
		'topbar' => elgg_view('page/elements/topbar', $vars),
		'header' => elgg_view('page/elements/header', $vars),
		'admin-notices' => elgg_view('page/elements/admin_notices', $vars),
		'body' => elgg_view('page/elements/body', $vars),
		'footer' => elgg_view('page/elements/footer', $vars),
	];
}

$page = '';
foreach ($sections as $section => $content) {
	$page .= elgg_view('page/elements/section', [
		'section' => $section,
		'html' => $content,
		'page_shell' => elgg_extract('page_shell', $vars),
	]);
}

$page_vars = elgg_extract('page_attrs', $vars, []);
$page_vars['class'] = elgg_extract_class($page_vars, ['elgg-page', 'elgg-page-default']);

$body = elgg_format_element('div', $page_vars, $page);

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', elgg_extract('head', $vars, []));

$params = [
	'head' => $head,
	'body' => $body,
	'body_attrs' => elgg_extract('body_attrs', $vars, []),
	'html_attrs' => elgg_extract('html_attrs', $vars, []),
];

echo elgg_view('page/elements/html', $params);
