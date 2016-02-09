<?php

// example of altering response object via hook. This might be done in a plugin

function developers_ajax_demo_alter($hook, $type, Elgg\Services\AjaxResponse $v, $p) {
	// check data added by client hook
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$v->getData()->server_response_altered = 2;
	}

	register_error('Error from ajax demo response hook');
}
elgg_register_plugin_hook_handler('ajax_response', 'action:developers/ajax_demo', 'developers_ajax_demo_alter');


// typical ajax action:

elgg_ajax_gatekeeper();

$arg1 = (int)get_input('arg1');
$arg2 = (int)get_input('arg2');

system_message('Success message from ajax demo');

echo json_encode([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
]);
