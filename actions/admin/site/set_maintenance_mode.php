<?php
/**
 * Configure site maintenance mode
 */

$mode = (int) get_input('mode');
$message = get_input('message');

$site = elgg_get_site_entity();

$result = elgg_save_config('elgg_maintenance_mode', $mode);

$result = $result && $site->setPrivateSetting('elgg_maintenance_message', $message);

if (!$result) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('admin:maintenance_mode:saved'));
