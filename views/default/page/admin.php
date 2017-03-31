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

$messages = elgg_view('page/elements/messages', ['object' => $vars['sysmessages']]);
$messages .= elgg_view('page/elements/admin_notices', $vars);

// render content before head so that JavaScript and CSS can be loaded. See #4032
$sections = [
	'topbar' => elgg_view('admin/topbar', $vars),
	'header' => elgg_view('admin/header', $vars),
	'messages' => $messages,
	'body' => elgg_extract('body', $vars),
	'footer' => elgg_view('admin/footer', $vars),
];

$vars['sections'] = $sections;

$page_attrs = (array) elgg_extract('page_attrs', $vars, []);
$vars['page_attrs']['class'] = elgg_extract_class($page_attrs, 'elgg-page-admin');

echo elgg_view('page/default', $vars);
