<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

elgg_unregister_external_file('css', 'elgg');
elgg_load_external_file('css', 'elgg.walled_garden');

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
$header = elgg_view('page/elements/walled_garden/header', $vars);
$footer = elgg_view('page/elements/walled_garden/footer', $vars);

$body = elgg_view('page/elements/walled_garden/body', [
	'messages' => $messages,
	'header' => $header,
	'content' => elgg_extract('body', $vars),
	'footer' => $footer,
]);
$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', elgg_extract('head', $vars, []));

echo elgg_view('page/elements/html', [
	'head' => $head,
	'body' => $body,
]);
