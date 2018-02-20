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

elgg_unregister_css('elgg');
elgg_load_css('elgg.walled_garden');

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
$content = elgg_extract('body', $vars);

$header = elgg_view('page/elements/walled_garden/header', $vars);
$footer = elgg_view('page/elements/walled_garden/footer', $vars);

$body = <<<__BODY
<div class="elgg-page elgg-page-walled-garden">
	<div class="elgg-page-walled-garden-background"></div>
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="elgg-inner">
		<div class="elgg-page-header">
			<div class="elgg-inner">
				$header
			</div>
		</div>
		<div class="elgg-page-body">
			<div class="elgg-inner">
				$content
			</div>
		</div>
		<div class="elgg-page-footer">
			<div class="elgg-inner">
				$footer
			</div>
		</div>
	</div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', elgg_extract('head', $vars, []));

$params = [
	'head' => $head,
	'body' => $body,
];

echo elgg_view('page/elements/html', $params);
