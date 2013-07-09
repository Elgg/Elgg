<?php
/**
 * Maintenance mode page shell
 * 
 * @uses $vars['body']
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = $vars["body"];

$body = <<<__BODY
<div class="elgg-page elgg-page-maintenance">
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="elgg-body-maintenance">
		$content
	</div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', $vars);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));