<?php
/**
 * Maintenance mode page shell
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

elgg_unregister_external_file('css', 'elgg');
elgg_load_external_file('css', 'maintenance');

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
$header = elgg_view('page/elements/maintenance/header', $vars);

$body = elgg_view('page/elements/maintenance/body', [
	'messages' => $messages,
	'header' => $header,
	'content' => elgg_extract('body', $vars),
]);

$head = elgg_view('page/elements/head', elgg_extract('head', $vars, []));

echo elgg_view('page/elements/html', [
	'head' => $head,
	'body' => $body,
]);
