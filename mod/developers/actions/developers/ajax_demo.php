<?php

/**
 * Example of altering response object via hook. This might be done in a plugin
 *
 * @param string                     $hook 'ajax_response'
 * @param string                     $type 'action:developers/ajax_demo'
 * @param Elgg\Services\AjaxResponse $v    current return value
 * @param mixed                      $p    supploed params
 *
 * @return void
 */
function developers_ajax_demo_alter($hook, $type, Elgg\Services\AjaxResponse $v, $p) {
	// check data added by client hook
	if (get_input('client_request_altered') == '1') {
		// add some data to the response
		$v->getData()->server_response_altered = 2;
	}

	register_error('Hello from ajax_response hook');
}
elgg_register_plugin_hook_handler('ajax_response', 'action:developers/ajax_demo', 'developers_ajax_demo_alter');

// typical ajax action:

elgg_ajax_gatekeeper();

$arg1 = (int) get_input('arg1');
$arg2 = (int) get_input('arg2');

system_message('Hello from action');

echo json_encode([
	'sum' => $arg1 + $arg2,
	'product' => $arg1 * $arg2,
]);
