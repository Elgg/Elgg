<?php

// example of altering response object via hook. This might be done in a plugin

function developers_ajax3_demo_alter($hook, $type, Elgg\Services\AjaxResponse $v, $p) {
	// check data added by client hook
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$v->getData()->server_response_altered = 2;
	}
}
elgg_register_plugin_hook_handler('ajax_response', 'action:developers/ajax3_demo', 'developers_ajax3_demo_alter');


// typical ajax action:

elgg_ajax_gatekeeper();

$arg1 = get_input('arg1');
$arg2 = get_input('arg2');

if ($arg1 === null) {
	register_error('Expected error: Arg1 is missing');
	// note, no explicit response code. But version 3 response will be 500 due to register_error()
	forward(REFERER);
}

if ($arg2 === null) {
	register_error('Expected error: Arg2 is missing');
	// explicit HTTP status
	elgg_set_response_code(ELGG_HTTP_BAD_REQUEST);
	forward(REFERER);
}

system_message('Success message from ajax demo');

echo json_encode([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
]);
