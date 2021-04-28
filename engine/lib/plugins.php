<?php
/**
 * Elgg plugins library
 * Contains functions for managing plugins
 */

/**
 * Discovers plugins in the plugins_path setting and creates \ElggPlugin
 * entities for them if they don't exist.  If there are plugins with entities
 * but not actual files, will disable the \ElggPlugin entities and mark as inactive.
 * The \ElggPlugin object holds config data, so don't delete.
 *
 * @return bool
 * @since 1.8.0
 * @internal
 */
function _elgg_generate_plugin_entities(): bool {
	return _elgg_services()->plugins->generateEntities();
}

/**
 * Returns an \ElggPlugin object with the path $path.
 *
 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
 *
 * @return \ElggPlugin|null
 * @since 1.8.0
 */
function elgg_get_plugin_from_id(string $plugin_id): ?\ElggPlugin {
	return _elgg_services()->plugins->get($plugin_id);
}

/**
 * Returns if a plugin exists in the system.
 *
 * @warning This checks only plugins that are registered in the system!
 * If the plugin cache is outdated, be sure to regenerate it with
 * {@link _elgg_generate_plugin_objects()} first.
 *
 * @param string $plugin_id The plugin ID.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_plugin_exists(string $plugin_id): bool {
	return _elgg_services()->plugins->exists($plugin_id);
}

/**
 * Returns if a plugin is active for a current site.
 *
 * @param string $plugin_id The plugin ID
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_is_active_plugin(string $plugin_id): bool {
	return _elgg_services()->plugins->isActive($plugin_id);
}

/**
 * Returns an ordered list of plugins
 *
 * @param string $status The status of the plugins. active, inactive, or all.
 *
 * @return \ElggPlugin[]
 * @since 1.8.0
 */
function elgg_get_plugins(string $status = 'active'): array {
	return _elgg_services()->plugins->find($status);
}

/**
 * Set a user specific setting for a plugin.
 *
 * @param string $name      The name. Note: cannot be "title".
 * @param mixed  $value     The value.
 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @see \ElggPlugin::setUserSetting()
 */
function elgg_set_plugin_user_setting(string $name, $value, int $user_guid = 0, string $plugin_id = ''): bool {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->setUserSetting($name, $value, $user_guid);
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name      The name of the setting.
 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id The plugin ID (Required)
 * @param mixed  $default   The default value to return if none is set
 *
 * @return mixed
 * @since 1.8.0
 * @see \ElggPlugin::getUserSetting()
 */
function elgg_get_plugin_user_setting(string $name, int $user_guid = 0, string $plugin_id = '', $default = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->getUserSetting($name, $user_guid, $default);
}

/**
 * Get setting for a plugin.
 *
 * @param string $name      The name of the setting.
 * @param string $plugin_id The plugin ID (Required)
 * @param mixed  $default   The default value to return if none is set
 *
 * @return mixed
 * @since 1.8.0
 * @see \ElggPlugin::getSetting()
 */
function elgg_get_plugin_setting(string $name, string $plugin_id, $default = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->getSetting($name, $default);
}
