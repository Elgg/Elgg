<?php

$output = get_input('output');
$forward_url = get_input('forward_url');
$forward_reason = (int) get_input('forward_reason', ELGG_HTTP_OK);
$system_message = get_input('system_message');
$error_message = get_input('error_message');

if (is_array($output)){
	echo json_encode($output);
} else {
	echo $output;
}
if ($system_message) {
	system_message($system_message);
}
if ($error_message) {
	register_error($error_message);
}
if ($forward_url || $forward_url === '') {
	if ($forward_url === '-1') {
		$forward_url = REFERRER;
	}
	_elgg_services()->responseFactory->redirect($forward_url, $forward_reason);
}