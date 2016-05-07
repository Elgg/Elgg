<?php

$output = elgg_extract('output', $vars, '');
$forward_url = elgg_extract('forward_url', $vars, REFERRER);
$forward_reason = elgg_extract('forward_reason', $vars, '');
$error = elgg_extract('error', $vars);

echo $output;

if ($error) {
	register_error($error);
}

_elgg_services()->responseFactory->redirect($forward_url, $forward_reason);