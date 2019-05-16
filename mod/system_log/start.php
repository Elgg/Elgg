<?php

/**
 * Elgg system log
 */

require_once __DIR__ . '/lib/system_log.php';

/**
 * Initializes the plugin
 * @return void
 */
function system_log_init() {
	/** Register event to listen to all events **/
	elgg_register_event_handler('all', 'all', 'system_log_listener', 400);

	/** Register a default system log handler */
	elgg_register_event_handler('log', 'systemlog', 'system_log_default_logger', 999);

	$disable_logging = function() {
		// disable the system log for upgrades to avoid exceptions when the schema changes.
		elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');
		elgg_unregister_event_handler('all', 'all', 'system_log_listener');
	};
	
	elgg_register_event_handler('upgrade:before', 'system', $disable_logging);
	elgg_register_event_handler('upgrade:execute:before', 'system', $disable_logging);

	// Register cron hook for archival of logs
	elgg_register_plugin_hook_handler('cron', 'all', 'system_log_archive_cron');

	// Register cron hook for deletion of selected archived logs
	elgg_register_plugin_hook_handler('cron', 'all', 'system_log_delete_cron');

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'system_log_user_hover_menu');

	elgg_register_menu_item('page', [
		'name' => 'administer_utilities:logbrowser',
		'text' => elgg_echo('admin:administer_utilities:logbrowser'),
		'href' => 'admin/administer_utilities/logbrowser',
		'section' => 'administer',
		'parent_name' => 'administer_utilities',
		'context' => 'admin',
	]);

	elgg_extend_view('core/settings/statistics', 'core/settings/account/login_history');
}

/**
 * Default system log handler, allows plugins to override, extend or disable logging.
 *
 * @param string   $event       Event name
 * @param string   $object_type Object type
 * @param Loggable $object      Object to log
 *
 * @return true
 */
function system_log_default_logger($event, $object_type, $object) {
	system_log($object['object'], $object['event']);

	return true;
}

/**
 * System log listener.
 * This function listens to all events in the system and logs anything appropriate.
 *
 * @param String   $event       Event name
 * @param String   $object_type Type of object
 * @param Loggable $object      Object to log
 *
 * @return true
 * @access private
 */
function system_log_listener($event, $object_type, $object) {
	if (($object_type != 'systemlog') && ($event != 'log')) {
		elgg_trigger_event('log', 'systemlog', ['object' => $object, 'event' => $event]);
	}

	return true;
}

/**
 * Add to the user hover menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function system_log_user_hover_menu($hook, $type, $return, $params) {

	$user = elgg_extract('entity', $params);
	if (!$user instanceof ElggUser) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'logbrowser',
		'href' => "admin/administer_utilities/logbrowser?user_guid={$user->guid}",
		'text' => elgg_echo('logbrowser:explore'),
		'icon' => 'search',
		'section' => 'admin',
	]);

	return $return;
}

/**
 * Trigger the log rotation
 *
 * @param string $hook        'cron'
 * @param string $type        interval
 * @param string $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void|string
 */
function system_log_archive_cron($hook, $type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logrotated");

	$period = elgg_get_plugin_setting('period', 'system_log');
	if ($period !== $type) {
		return;
	}
	$offset = system_log_get_seconds_in_period($period);

	if (!system_log_archive_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotrotated");
	}

	return $returnvalue . $resulttext;
}

/**
 * Trigger the log deletion
 *
 * @param string $hook        'cron'
 * @param string $type        interval
 * @param string $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void|string
 */
function system_log_delete_cron($hook, $type, $returnvalue, $params) {
	$resulttext = elgg_echo("logrotate:logdeleted");

	$period = elgg_get_plugin_setting('delete', 'system_log');
	if ($period == 'never') {
		return;
	}

	if ($period !== $type) {
		return;
	}

	$offset = system_log_get_seconds_in_period($period);

	if (!system_log_browser_delete_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotdeleted");
	}

	return $returnvalue . $resulttext;
}

return function() {
	elgg_register_event_handler('init', 'system', 'system_log_init');
};
