<?php

elgg_register_event_handler('ajax_results', 'action:theme_sandbox/ajax_demo', function(\Elgg\Event $event) {
	/* @var $results \stdClass */
	$results = $event->getValue();
	
	// check data added by client event
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$results->server_response_altered = 2;
	}
	
	elgg_register_error_message('Hello from ajax_results event');
	
	return $results;
});

$arg1 = (int) get_input('arg1');
$arg2 = (int) get_input('arg2');

return elgg_ok_response([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
], 'Hello from action');
