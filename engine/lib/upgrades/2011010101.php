<?php
/**
 * Migrate plugins to the new system using ElggPlugin and private settings
 */

$old_ia = elgg_set_ignore_access(true);

$site = get_config('site');
$old_plugin_order = unserialize($site->pluginorder);
$old_enabled_plugins = $site->enabled_plugins;

$db_prefix = get_config('dbprefix');
$plugin_subtype_id = get_subtype_id('object', 'plugin');

// easy one first: make sure the the site owns all plugin entities.
$q = "UPDATE {$db_prefix}entities e
	SET owner_guid = $site->guid, container_guid = $site->guid
	WHERE e.type = 'object' AND e.subtype = $plugin_subtype_id";

$r = update_data($q);

// rewrite all plugin:setting:* to ELGG_PLUGIN_USER_SETTING_PREFIX . *
$q = "UPDATE {$db_prefix}private_settings
	SET name = replace(name, 'plugin:settings:', '" . ELGG_PLUGIN_USER_SETTING_PREFIX . "')
	WHERE name LIKE 'plugin:settings:%'";

$r = update_data($q);

// grab current plugin GUIDs to add a temp priority
$q = "SELECT * FROM {$db_prefix}entities e
	JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid
	WHERE e.type = 'object' AND e.subtype = $plugin_subtype_id";

$plugins = get_data($q);

foreach ($plugins as $plugin) {
	$priority = elgg_namespace_plugin_private_setting('internal', 'priority');
	set_private_setting($plugin->guid, $priority, 0);
}

// force regenerating plugin entities
elgg_generate_plugin_entities();

// set the priorities for all plugins
// this function rewrites it to a normal index so use the current one.
elgg_set_plugin_priorities($old_plugin_order);

// add relationships for enabled plugins
if ($old_enabled_plugins) {
	// they might only have one plugin enabled.
	if (!is_array($old_enabled_plugins)) {
		$old_enabled_plugins = array($old_enabled_plugins);
	}

	// sometimes there were problems and you'd get 1000s of enabled plugins.
	$old_enabled_plugins = array_unique($old_enabled_plugins);

	foreach ($old_enabled_plugins as $plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);

		if ($plugin) {
			$plugin->activate();
		}
	}
}

// invalidate caches
elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

// clean up.
remove_metadata($site->guid, 'pluginorder');
remove_metadata($site->guid, 'enabled_plugins');

elgg_set_ignore_access($old_id);

/**
 * @hack
 *
 * We stop the upgrade at this point because plugins weren't given the chance to
 * load due to the new plugin code introduced with Elgg 1.8. Instead, we manually
 * set the version and start the upgrade process again.
 *
 * The variables from upgrade_code() are available because this script was included
 */
if ($upgrade_version > $version) {
	datalist_set('version', $upgrade_version);
}

// add ourselves to the processed_upgrades.
$processed_upgrades[] = '2011010101.php';

$processed_upgrades = array_unique($processed_upgrades);
elgg_set_processed_upgrades($processed_upgrades);

forward('upgrade.php');
