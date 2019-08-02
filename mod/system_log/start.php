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
 * @param \Elgg\Event $event 'log', 'systemlog'
 *
 * @return true
 */
function system_log_default_logger(\Elgg\Event $event) {
	$object = $event->getObject();
	system_log($object['object'], $object['event']);

	return true;
}

/**
 * System log listener.
 * This function listens to all events in the system and logs anything appropriate.
 *
 * @param \Elgg\Event $event 'all', 'all'
 *
 * @return true
 * @internal
 */
function system_log_listener(\Elgg\Event $event) {
	if (($event->getType() != 'systemlog') && ($event->getName() != 'log')) {
		elgg_trigger_event('log', 'systemlog', ['object' => $event->getObject(), 'event' => $event->getName()]);
	}

	return true;
}

/**
 * Add to the user hover menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
 *
 * @return void|ElggMenuItem[]
 */
function system_log_user_hover_menu(\Elgg\Hook $hook) {

	$user = $hook->getEntityParam();
	if (!$user instanceof ElggUser) {
		return;
	}

	$return = $hook->getValue();
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
 * @param \Elgg\Hook $hook 'cron', 'all'
 *
 * @return void|string
 */
function system_log_archive_cron(\Elgg\Hook $hook) {
	$resulttext = elgg_echo("logrotate:logrotated");

	$period = elgg_get_plugin_setting('period', 'system_log');
	if ($period !== $hook->getType()) {
		return;
	}
	$offset = system_log_get_seconds_in_period($period);

	if (!system_log_archive_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotrotated");
	}

	return $hook->getValue() . $resulttext;
}

/**
 * Trigger the log deletion
 *
 * @param \Elgg\Hook $hook 'cron', 'all'
 *
 * @return void|string
 */
function system_log_delete_cron(\Elgg\Hook $hook) {
	$resulttext = elgg_echo("logrotate:logdeleted");

	$period = elgg_get_plugin_setting('delete', 'system_log');
	if ($period == 'never') {
		return;
	}

	if ($period !== $hook->getType()) {
		return;
	}

	$offset = system_log_get_seconds_in_period($period);

	if (!system_log_browser_delete_log($offset)) {
		$resulttext = elgg_echo("logrotate:lognotdeleted");
	}

	return $hook->getValue() . $resulttext;
}

return function() {
	elgg_register_event_handler('init', 'system', 'system_log_init');
};
