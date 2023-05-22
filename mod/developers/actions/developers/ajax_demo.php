<?php

elgg_register_event_handler('ajax_response', 'action:developers/ajax_demo', function(\Elgg\Event $event) {
	/* @var $response \Elgg\Services\AjaxResponse */
	$response = $event->getValue();
	
	// check data added by client event
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$response->getData()->server_response_altered = 2;
	}
	
	elgg_register_error_message('Hello from ajax_response event');
	
	return $response;
});

$arg1 = (int) get_input('arg1');
$arg2 = (int) get_input('arg2');

return elgg_ok_response([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
], 'Hello from action');
