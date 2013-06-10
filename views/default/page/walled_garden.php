<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = $vars["body"];

$body = <<<__BODY
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="elgg-body-walledgarden">
		$content
	</div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', $vars);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
