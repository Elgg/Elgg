<?php
/**
 * Elgg pageshell for the admin area
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

elgg_load_css('elgg.admin');

$messages = elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
$messages .= elgg_view('page/elements/admin_notices', $vars);

// render content before head so that JavaScript and CSS can be loaded. See #4032
$sections = [
	'topbar' => elgg_view('admin/header', $vars),
	'messages' => $messages,
	'body' => elgg_extract('body', $vars),
	'footer' => elgg_view('admin/footer', $vars),
];

$page = '';
foreach ($sections as $section => $content) {
	$page .= elgg_view('page/elements/section', [
		'section' => $section,
		'html' => $content,
		'page_shell' => elgg_extract('page_shell', $vars),
	]);
}

$page = elgg_format_element('div', ['class' => 'elgg-inner'], $page);

$page_vars = elgg_extract('page_attrs', $vars, []);
$page_vars['class'] = elgg_extract_class($page_vars, ['elgg-page', 'elgg-page-admin']);

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
