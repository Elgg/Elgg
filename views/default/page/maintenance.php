<?php
/**
 * Maintenance mode page shell
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
$content = elgg_extract('body', $vars);

elgg_unregister_external_file('css', 'elgg');
elgg_load_external_file('css', 'maintenance');

$body = <<<__BODY
<div class="elgg-page elgg-page-maintenance" id="elgg-maintenance-page-wrapper">
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="elgg-body-maintenance">
		$content
	</div>
</div>
__BODY;

$head = elgg_view('page/elements/head', elgg_extract('head', $vars, []));

echo elgg_view('page/elements/html', [
	'head' => $head,
	'body' => $body,
]);
