<?php
/**
 * The current value for the hook is passed into the trigger function. Handlers
 * can change this value. In this snippet, we check if the value of true was
 * changed by the handler functions.
 */

$result = elgg_trigger_plugin_hook('get_status', 'example', null, true);

if ($result) {
	var_dump('Plugin hook says ok!');
} else {
	var_dump('Plugin hook says no.');
}
