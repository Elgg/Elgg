<?php
/**
 * Configure site maintenance mode
 */

$mode = (int)get_input('mode');
$message = get_input('message');

$site = elgg_get_site_entity();

$result = elgg_save_config('elgg_maintenance_mode', $mode, null);

$result = $result && $site->setPrivateSetting('elgg_maintenance_message', $message);

if ($result) {
	system_message(elgg_echo('admin:maintenance_mode:saved'));
} else {
	register_error(elgg_echo('save:fail'));
}
