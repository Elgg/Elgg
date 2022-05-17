<?php

elgg_register_plugin_hook_handler('ajax_response', 'action:developers/ajax_demo', function(\Elgg\Hook $hook) {
	/* @var $response \Elgg\Services\AjaxResponse */
	$response = $hook->getValue();
	
	// check data added by client hook
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$response->getData()->server_response_altered = 2;
	}
	
	elgg_register_error_message('Hello from ajax_response hook');
	
	return $response;
});

$arg1 = (int) get_input('arg1');
$arg2 = (int) get_input('arg2');

return elgg_ok_response([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
], 'Hello from action');
