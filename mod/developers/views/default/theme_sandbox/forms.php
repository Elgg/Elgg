<?php

$body = elgg_view('theme_sandbox/forms/body');
$body .= elgg_view('theme_sandbox/forms/footer');

echo elgg_view('input/form', array(
	'action' => '#',
	'method' => 'GET',
	'body' => $body,
));