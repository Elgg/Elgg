<?php

$output = get_input('output');
$forward_url = get_input('forward_url');
$forward_reason = (int) get_input('forward_reason', ELGG_HTTP_OK);
$system_message = get_input('system_message');
$error_message = get_input('error_message');

if ($forward_url == '-1') {
	$forward_url = REFERRER;
}
if ($forward_reason == ELGG_HTTP_OK && !$error_message) {
	return elgg_ok_response($output, $system_message, $forward_url, $forward_reason);
} else if ($forward_reason == ELGG_HTTP_BAD_REQUEST || $error_message) {
	return elgg_error_response($error_message, $forward_url, $forward_reason);
} else if ($forward_reason == ELGG_HTTP_FOUND) {
	return elgg_redirect_response($forward_url);
}
