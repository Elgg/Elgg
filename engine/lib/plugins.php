<?php
/**
 * Elgg plugins library
 * Contains functions for managing plugins
 */

use Elgg\Menu\MenuItems;

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
function _elgg_generate_plugin_entities() {
	return _elgg_services()->plugins->generateEntities();
}

/**
 * Returns an \ElggPlugin object with the path $path.
 *
 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
 * @return \ElggPlugin|null
 * @since 1.8.0
 */
function elgg_get_plugin_from_id($plugin_id) {
	return _elgg_services()->plugins->get($plugin_id);
}

/**
 * Returns if a plugin exists in the system.
 *
 * @warning This checks only plugins that are registered in the system!
 * If the plugin cache is outdated, be sure to regenerate it with
 * {@link _elgg_generate_plugin_objects()} first.
 *
 * @param string $id The plugin ID.
 * @since 1.8.0
 * @return bool
 */
function elgg_plugin_exists($id) {
	return _elgg_services()->plugins->exists($id);
}

/**
 * Returns the highest priority of the plugins
 *
 * @return int
 * @since 1.8.0
 * @internal
 */
function _elgg_get_max_plugin_priority() {
	return _elgg_services()->plugins->getMaxPriority();
}

/**
 * Returns if a plugin is active for a current site.
 *
 * @param string $plugin_id The plugin ID
 * @since 1.8.0
 * @return bool
 */
function elgg_is_active_plugin($plugin_id) {
	return _elgg_services()->plugins->isActive($plugin_id);
}

/**
 * Returns an ordered list of plugins
 *
 * @param string $status The status of the plugins. active, inactive, or all.
 * @return \ElggPlugin[]
 * @since 1.8.0
 */
function elgg_get_plugins($status = 'active') {
	return _elgg_services()->plugins->find($status);
}

/**
 * Returns an array of all plugin user settings for a user.
 *
 * @param int    $user_guid  The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id  The plugin ID (Required)
 * @param bool   $return_obj Return settings as an object? This can be used to in reusable
 *                           views where the settings are passed as $vars['entity'].
 *
 * @return array|object
 * @since 1.8.0
 * @see   \ElggPlugin::getAllUserSettings()
 */
function elgg_get_all_plugin_user_settings($user_guid = 0, $plugin_id = null, $return_obj = false) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return [];
	}

	$settings = $plugin->getAllUserSettings($user_guid);

	return $return_obj ? (object) $settings : $settings;
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
function elgg_set_plugin_user_setting($name, $value, $user_guid = 0, $plugin_id = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->setUserSetting($name, $value, (int) $user_guid);
}

/**
 * Unsets a user-specific plugin setting
 *
 * @param string $name      Name of the setting
 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @see \ElggPlugin::unsetUserSetting()
 */
function elgg_unset_plugin_user_setting($name, $user_guid = 0, $plugin_id = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->unsetUserSetting($name, (int) $user_guid);
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
function elgg_get_plugin_user_setting($name, $user_guid = 0, $plugin_id = null, $default = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->getUserSetting($name, (int) $user_guid, $default);
}

/**
 * Set a setting for a plugin.
 *
 * @param string $name      The name of the setting - note, can't be "title".
 * @param mixed  $value     The value.
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @see \ElggPlugin::setSetting()
 */
function elgg_set_plugin_setting($name, $value, $plugin_id) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->setSetting($name, $value);
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
function elgg_get_plugin_setting($name, $plugin_id, $default = null) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->getSetting($name, $default);
}

/**
 * Unsets a plugin setting.
 *
 * @param string $name      The name of the setting.
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @see \ElggPlugin::unsetSetting()
 */
function elgg_unset_plugin_setting($name, $plugin_id) {
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->unsetSetting($name);
}

/**
 * Returns entities based upon plugin user settings.
 * Takes all the options for {@link elgg_get_entities_from_private_settings()}
 * in addition to the ones below.
 *
 * @param array $options Array in the format:
 *
 * 	plugin_id => STR The plugin id. Required.
 *
 * 	plugin_user_setting_names => null|ARR private setting names
 *
 * 	plugin_user_setting_values => null|ARR metadata values
 *
 * 	plugin_user_setting_name_value_pairs => null|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                        )
 * 	                             Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *
 * 	plugin_user_setting_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 * @return mixed int If count, int. If not count, array. false on errors.
 * @since 1.8.0
 */
function elgg_get_entities_from_plugin_user_settings(array $options = []) {
	return _elgg_services()->plugins->getEntitiesFromUserSettings($options);
}

/**
 * Registers menu items for the entity menu of a plugin
 *
 * @param \Elgg\Hook $hook 'register', 'menu:entity'
 *
 * @return void|MenuItems
 *
 * @internal
 */
function _elgg_plugin_entity_menu_setup(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity instanceof \ElggPlugin || !$entity->canEdit()) {
		return;
	}
	
	/** @var $return MenuItems **/
	$return = $hook->getValue();
	$return->remove('delete');
	
	if (elgg_view_exists("plugins/{$entity->getID()}/settings")) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings',
			'href' => "admin/plugin_settings/{$entity->getID()}",
			'text' => elgg_echo('settings'),
			'icon' => 'settings-alt',
			'section' => 'admin'
		]);
	}
	
	$priority = $entity->getPriority();
	
	// top and up link only if not at top
	if ($priority > 1) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'top',
			'href' => elgg_generate_action_url('admin/plugins/set_priority', [
				'plugin_guid' => $entity->guid,
				'priority' => 'first',
			]),
			'text' => elgg_echo('top'),
			'icon' => 'angle-double-up',
			'priority' => 11,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'up',
			'href' => elgg_generate_action_url('admin/plugins/set_priority', [
				'plugin_guid' => $entity->guid,
				'priority' => '-1',
			]),
			'text' => elgg_echo('up'),
			'icon' => 'angle-up',
			'priority' => 12,
		]);
	}
	
	// down and bottom links only if not at bottom
	if ($priority < _elgg_get_max_plugin_priority()) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'down',
			'href' => elgg_generate_action_url('admin/plugins/set_priority', [
				'plugin_guid' => $entity->guid,
				'priority' => '+1',
			]),
			'text' => elgg_echo('down'),
			'icon' => 'angle-down',
			'priority' => 13,
		]);

		$return[] = \ElggMenuItem::factory([
			'name' => 'bottom',
			'href' => elgg_generate_action_url('admin/plugins/set_priority', [
				'plugin_guid' => $entity->guid,
				'priority' => 'last',
			]),
			'text' => elgg_echo('bottom'),
			'icon' => 'angle-double-down',
			'priority' => 14,
		]);
	}
	
	// remove all user and plugin settings
	$return[] = \ElggMenuItem::factory([
		'name' => 'remove_settings',
		'href' => elgg_generate_action_url('plugins/settings/remove', [
			'plugin_id' => $entity->getID(),
		]),
		'text' => elgg_echo('plugins:settings:remove:menu:text'),
		'icon' => 'trash-alt',
		'confirm' => elgg_echo('plugins:settings:remove:menu:confirm'),
	]);
	
	return $return;
}

/**
 * Initialize the plugin system
 *
 * @return void
 * @internal
 */
function _elgg_plugins_init() {
	elgg_register_plugin_hook_handler('register', 'menu:entity', '_elgg_plugin_entity_menu_setup');
	
	elgg_register_ajax_view('object/plugin/full');
	elgg_register_ajax_view('object/plugin/details');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_plugins_init');
};
