<?php
/**
 * Elgg plugins library
 * Contains functions for managing plugins
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

/**
 * Tells ElggPlugin::start() to include the start.php file.
 */
define('ELGG_PLUGIN_INCLUDE_START', 1);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's views.
 */
define('ELGG_PLUGIN_REGISTER_VIEWS', 2);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's languages.
 */
define('ELGG_PLUGIN_REGISTER_LANGUAGES', 4);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's classes.
 */
define('ELGG_PLUGIN_REGISTER_CLASSES', 8);

/**
 * Prefix for plugin setting names
 *
 * @todo Can't namespace these because many plugins directly call
 * private settings via $entity->$name.
 */
//define('ELGG_PLUGIN_SETTING_PREFIX', 'plugin:setting:');

/**
 * Prefix for plugin user setting names
 */
define('ELGG_PLUGIN_USER_SETTING_PREFIX', 'plugin:user_setting:');

/**
 * Internal settings prefix
 *
 * @todo This could be resolved by promoting ElggPlugin to a 5th type.
 */
define('ELGG_PLUGIN_INTERNAL_PREFIX', 'elgg:internal:');


/**
 * Returns a list of plugin directory names from a base directory.
 *
 * @param string $dir A dir to scan for plugins. Defaults to config's plugins_path.
 *                    Must have a trailing slash.
 *
 * @return array Array of directory names (not full paths)
 * @since 1.8.0
 * @access private
 */
function _elgg_get_plugin_dirs_in_dir($dir = null) {
	if (!$dir) {
		$dir = elgg_get_plugins_path();
	}

	$plugin_dirs = array();
	$handle = opendir($dir);

	if ($handle) {
		while ($plugin_dir = readdir($handle)) {
			// must be directory and not begin with a .
			if (substr($plugin_dir, 0, 1) !== '.' && is_dir($dir . $plugin_dir)) {
				$plugin_dirs[] = $plugin_dir;
			}
		}
	}

	sort($plugin_dirs);

	return $plugin_dirs;
}

/**
 * Discovers plugins in the plugins_path setting and creates ElggPlugin
 * entities for them if they don't exist.  If there are plugins with entities
 * but not actual files, will disable the ElggPlugin entities and mark as inactive.
 * The ElggPlugin object holds config data, so don't delete.
 *
 * @return bool
 * @since 1.8.0
 * @access private
 */
function _elgg_generate_plugin_entities() {

	$mod_dir = elgg_get_plugins_path();
	$db_prefix = elgg_get_config('dbprefix');

	// ignore access in case this is called with no admin logged in - needed for creating plugins perhaps?
	$old_ia = elgg_set_ignore_access(true);

	// show hidden entities so that we can enable them if appropriate
	$old_access = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'selects' => array('plugin_oe.*'),
		'joins' => array("JOIN {$db_prefix}objects_entity plugin_oe on plugin_oe.guid = e.guid"),
		'limit' => ELGG_ENTITIES_NO_VALUE,
	);
	$known_plugins = elgg_get_entities_from_relationship($options);
	/* @var ElggPlugin[] $known_plugins */

	if (!$known_plugins) {
		$known_plugins = array();
	}

	// map paths to indexes
	$id_map = array();
	foreach ($known_plugins as $i => $plugin) {
		// if the ID is wrong, delete the plugin because we can never load it.
		$id = $plugin->getID();
		if (!$id) {
			$plugin->delete();
			unset($known_plugins[$i]);
			continue;
		}
		$id_map[$plugin->getID()] = $i;
	}

	$physical_plugins = _elgg_get_plugin_dirs_in_dir($mod_dir);

	if (!$physical_plugins) {
		return false;
	}

	// check real plugins against known ones
	foreach ($physical_plugins as $plugin_id) {
		// is this already in the db?
		if (array_key_exists($plugin_id, $id_map)) {
			$index = $id_map[$plugin_id];
			$plugin = $known_plugins[$index];
			// was this plugin deleted and its entity disabled?
			if (!$plugin->isEnabled()) {
				$plugin->enable();
				$plugin->deactivate();
				$plugin->setPriority('last');
			}

			// remove from the list of plugins to disable
			unset($known_plugins[$index]);
		} else {
			// create new plugin
			// priority is forced to last in save() if not set.
			$plugin = new ElggPlugin($mod_dir . $plugin_id);
			$plugin->save();
		}
	}

	// everything remaining in $known_plugins needs to be disabled
	// because they are entities, but their dirs were removed.
	// don't delete the entities because they hold settings.
	foreach ($known_plugins as $plugin) {
		if ($plugin->isActive()) {
			$plugin->deactivate();
		}
		// remove the priority.
		$name = _elgg_namespace_plugin_private_setting('internal', 'priority');
		remove_private_setting($plugin->guid, $name);
		if ($plugin->isEnabled()) {
			$plugin->disable();
		}
	}

	access_show_hidden_entities($old_access);
	elgg_set_ignore_access($old_ia);

	_elgg_reindex_plugin_priorities();

	return true;
}

/**
 * Cache a reference to this plugin by its ID
 *
 * @param ElggPlugin $plugin
 *
 * @access private
 */
function _elgg_cache_plugin_by_id(ElggPlugin $plugin) {
	$map = (array) elgg_get_config('plugins_by_id_map');
	$map[$plugin->getID()] = $plugin;
	elgg_set_config('plugins_by_id_map', $map);
}

/**
 * Returns an ElggPlugin object with the path $path.
 *
 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
 * @return ElggPlugin|null
 * @since 1.8.0
 */
function elgg_get_plugin_from_id($plugin_id) {
	$map = (array) elgg_get_config('plugins_by_id_map');
	if (isset($map[$plugin_id])) {
		return $map[$plugin_id];
	}

	$plugin_id = sanitize_string($plugin_id);
	$db_prefix = get_config('dbprefix');

	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'joins' => array("JOIN {$db_prefix}objects_entity oe on oe.guid = e.guid"),
		'selects' => array("oe.title", "oe.description"),
		'wheres' => array("oe.title = '$plugin_id'"),
		'limit' => 1,
	);

	$plugins = elgg_get_entities($options);

	if ($plugins) {
		return $plugins[0];
	}

	return null;
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
	$plugin = elgg_get_plugin_from_id($id);

	return ($plugin) ? true : false;
}

/**
 * Returns the highest priority of the plugins
 *
 * @return int
 * @since 1.8.0
 * @access private
 */
function _elgg_get_max_plugin_priority() {
	$db_prefix = get_config('dbprefix');
	$priority = _elgg_namespace_plugin_private_setting('internal', 'priority');
	$plugin_subtype = get_subtype_id('object', 'plugin');

	$q = "SELECT MAX(CAST(ps.value AS unsigned)) as max
		FROM {$db_prefix}entities e, {$db_prefix}private_settings ps
		WHERE ps.name = '$priority'
		AND ps.entity_guid = e.guid
		AND e.type = 'object' and e.subtype = $plugin_subtype";

	$data = get_data($q);
	if ($data) {
		$max = $data[0]->max;
	} else {
		$max = 1;
	}

	// can't have a priority of 0.
	return ($max) ? $max : 1;
}

/**
 * Returns if a plugin is active for a current site.
 *
 * @param string $plugin_id The plugin ID
 * @param int    $site_guid The site guid
 * @since 1.8.0
 * @return bool
 */
function elgg_is_active_plugin($plugin_id, $site_guid = null) {
	if ($site_guid) {
		$site = get_entity($site_guid);
	} else {
		$site = elgg_get_site_entity();
	}

	if (!($site instanceof ElggSite)) {
		return false;
	}

	$plugin = elgg_get_plugin_from_id($plugin_id);

	if (!$plugin) {
		return false;
	}

	return $plugin->isActive($site->guid);
}

/**
 * Loads all active plugins in the order specified in the tool admin panel.
 *
 * @note This is called on every page load. If a plugin is active and problematic, it
 * will be disabled and a visible error emitted. This does not check the deps system because
 * that was too slow.
 *
 * @return bool
 * @since 1.8.0
 * @access private
 */
function _elgg_load_plugins() {
	$plugins_path = elgg_get_plugins_path();
	$start_flags = ELGG_PLUGIN_INCLUDE_START |
					ELGG_PLUGIN_REGISTER_VIEWS |
					ELGG_PLUGIN_REGISTER_LANGUAGES |
					ELGG_PLUGIN_REGISTER_CLASSES;

	if (!$plugins_path) {
		return false;
	}

	// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
	if (file_exists("$plugins_path/disabled")) {
		if (elgg_is_admin_logged_in() && elgg_in_context('admin')) {
			system_message(elgg_echo('plugins:disabled'));
		}
		return false;
	}

	if (elgg_get_config('system_cache_loaded')) {
		$start_flags = $start_flags & ~ELGG_PLUGIN_REGISTER_VIEWS;
	}

	if (elgg_get_config('i18n_loaded_from_cache')) {
		$start_flags = $start_flags & ~ELGG_PLUGIN_REGISTER_LANGUAGES;
	}

	$return = true;
	$plugins = elgg_get_plugins('active');
	if ($plugins) {
		foreach ($plugins as $plugin) {
			try {
				$plugin->start($start_flags);
			} catch (Exception $e) {
				$plugin->deactivate();
				$msg = elgg_echo('PluginException:CannotStart',
								array($plugin->getID(), $plugin->guid, $e->getMessage()));
				elgg_add_admin_notice('cannot_start' . $plugin->getID(), $msg);
				$return = false;

				continue;
			}
		}
	}

	return $return;
}

/**
 * Returns an ordered list of plugins
 *
 * @param string $status    The status of the plugins. active, inactive, or all.
 * @param mixed  $site_guid Optional site guid
 * @return ElggPlugin[]
 * @since 1.8.0
 */
function elgg_get_plugins($status = 'active', $site_guid = null) {
	$db_prefix = get_config('dbprefix');
	$priority = _elgg_namespace_plugin_private_setting('internal', 'priority');

	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	// grab plugins
	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'limit' => ELGG_ENTITIES_NO_VALUE,
		'selects' => array('plugin_oe.*'),
		'joins' => array(
			"JOIN {$db_prefix}private_settings ps on ps.entity_guid = e.guid",
			"JOIN {$db_prefix}objects_entity plugin_oe on plugin_oe.guid = e.guid"
			),
		'wheres' => array("ps.name = '$priority'"),
		'order_by' => "CAST(ps.value as unsigned), e.guid"
	);

	switch ($status) {
		case 'active':
			$options['relationship'] = 'active_plugin';
			$options['relationship_guid'] = $site_guid;
			$options['inverse_relationship'] = true;
			break;

		case 'inactive':
			$options['wheres'][] = "NOT EXISTS (
					SELECT 1 FROM {$db_prefix}entity_relationships active_er
					WHERE active_er.guid_one = e.guid
						AND active_er.relationship = 'active_plugin'
						AND active_er.guid_two = $site_guid)";
			break;

		case 'all':
		default:
			break;
	}

	$old_ia = elgg_set_ignore_access(true);
	$plugins = elgg_get_entities_from_relationship($options);
	elgg_set_ignore_access($old_ia);

	return $plugins;
}

/**
 * Reorder plugins to an order specified by the array.
 * Plugins not included in this array will be appended to the end.
 *
 * @note This doesn't use the ElggPlugin->setPriority() method because
 *       all plugins are being changed and we don't want it to automatically
 *       reorder plugins.
 *
 * @param array $order An array of plugin ids in the order to set them
 * @return bool
 * @since 1.8.0
 * @access private
 */
function _elgg_set_plugin_priorities(array $order) {
	$name = _elgg_namespace_plugin_private_setting('internal', 'priority');

	$plugins = elgg_get_plugins('any');
	if (!$plugins) {
		return false;
	}

	$return = true;

	// reindex to get standard counting. no need to increment by 10.
	// though we do start with 1
	$order = array_values($order);

	$missing_plugins = array();
	/* @var ElggPlugin[] $missing_plugins */

	foreach ($plugins as $plugin) {
		$plugin_id = $plugin->getID();

		if (!in_array($plugin_id, $order)) {
			$missing_plugins[] = $plugin;
			continue;
		}

		$priority = array_search($plugin_id, $order) + 1;

		if (!$plugin->setPrivateSetting($name, $priority)) {
			$return = false;
			break;
		}
	}

	// set the missing plugins' priorities
	if ($return && $missing_plugins) {
		if (!isset($priority)) {
			$priority = 0;
		}
		foreach ($missing_plugins as $plugin) {
			$priority++;
			if (!$plugin->setPrivateSetting($name, $priority)) {
				$return = false;
				break;
			}
		}
	}

	return $return;
}

/**
 * Reindexes all plugin priorities starting at 1.
 *
 * @todo Can this be done in a single sql command?
 * @return bool
 * @since 1.8.0
 * @access private
 */
function _elgg_reindex_plugin_priorities() {
	return _elgg_set_plugin_priorities(array());
}

/**
 * Namespaces a string to be used as a private setting name for a plugin.
 *
 * For user_settings, two namespaces are added: a user setting namespace and the
 * plugin id.
 *
 * For internal (plugin priority), there is a single internal namespace added.
 *
 * @param string $type The type of setting: user_setting or internal.
 * @param string $name The name to namespace.
 * @param string $id   The plugin's ID to namespace with.  Required for user_setting.
 * @return string
 * @since 1.8.0
 * @access private
 */
function _elgg_namespace_plugin_private_setting($type, $name, $id = null) {
	switch ($type) {
		// commented out because it breaks $plugin->$name access to variables
		//case 'setting':
		//	$name = ELGG_PLUGIN_SETTING_PREFIX . $name;
		//	break;

		case 'user_setting':
			if (!$id) {
				elgg_deprecated_notice("You must pass the plugin id to _elgg_namespace_plugin_private_setting() for user settings", 1.9);
				$id = elgg_get_calling_plugin_id();
			}
			$name = ELGG_PLUGIN_USER_SETTING_PREFIX . "$id:$name";
			break;

		case 'internal':
			$name = ELGG_PLUGIN_INTERNAL_PREFIX . $name;
			break;
	}

	return $name;
}

/**
 * @var array cache used by elgg_get_plugins_provides function
 * @todo move it with all other functions to Elgg_PluginsService
 */
global $ELGG_PLUGINS_PROVIDES_CACHE;

/**
 * Returns an array of all provides from all active plugins.
 *
 * Array in the form array(
 * 	'provide_type' => array(
 * 		'provided_name' => array(
 * 			'version' => '1.8',
 * 			'provided_by' => 'provider_plugin_id'
 *  	)
 *  )
 * )
 *
 * @param string $type The type of provides to return
 * @param string $name A specific provided name to return. Requires $provide_type.
 *
 * @return array
 * @since 1.8.0
 * @access private
 */
function _elgg_get_plugins_provides($type = null, $name = null) {
	global $ELGG_PLUGINS_PROVIDES_CACHE;
	if (!isset($ELGG_PLUGINS_PROVIDES_CACHE)) {
		$active_plugins = elgg_get_plugins('active');

		$provides = array();

		foreach ($active_plugins as $plugin) {
			$plugin_provides = array();
			$manifest = $plugin->getManifest();
			if ($manifest instanceof ElggPluginManifest) {
				$plugin_provides = $plugin->getManifest()->getProvides();
			}
			if ($plugin_provides) {
				foreach ($plugin_provides as $provided) {
					$provides[$provided['type']][$provided['name']] = array(
						'version' => $provided['version'],
						'provided_by' => $plugin->getID()
					);
				}
			}
		}

		$ELGG_PLUGINS_PROVIDES_CACHE = $provides;
	}

	if ($type && $name) {
		if (isset($ELGG_PLUGINS_PROVIDES_CACHE[$type][$name])) {
			return $ELGG_PLUGINS_PROVIDES_CACHE[$type][$name];
		} else {
			return false;
		}
	} elseif ($type) {
		if (isset($ELGG_PLUGINS_PROVIDES_CACHE[$type])) {
			return $ELGG_PLUGINS_PROVIDES_CACHE[$type];
		} else {
			return false;
		}
	}

	return $ELGG_PLUGINS_PROVIDES_CACHE;
}

/**
 * Deletes all cached data on plugins being provided.
 *
 * @return boolean
 * @since 1.9.0
 * @access private
 */
function _elgg_invalidate_plugins_provides_cache() {
	global $ELGG_PLUGINS_PROVIDES_CACHE;
	$ELGG_PLUGINS_PROVIDES_CACHE = null;
	return true;
}

/**
 * Checks if a plugin is currently providing $type and $name, and optionally
 * checking a version.
 *
 * @param string $type       The type of the provide
 * @param string $name       The name of the provide
 * @param string $version    A version to check against
 * @param string $comparison The comparison operator to use in version_compare()
 *
 * @return array An array in the form array(
 * 	'status' => bool Does the provide exist?,
 * 	'value' => string The version provided
 * )
 * @since 1.8.0
 * @access private
 */
function _elgg_check_plugins_provides($type, $name, $version = null, $comparison = 'ge') {
	$provided = _elgg_get_plugins_provides($type, $name);
	if (!$provided) {
		return array(
			'status' => false,
			'value' => ''
		);
	}

	if ($version) {
		$status = version_compare($provided['version'], $version, $comparison);
	} else {
		$status = true;
	}

	return array(
		'status' => $status,
		'value' => $provided['version']
	);
}

/**
 * Returns an array of parsed strings for a dependency in the
 * format: array(
 * 	'type'			=>	requires, conflicts, or provides.
 * 	'name'			=>	The name of the requirement / conflict
 * 	'value'			=>	A string representing the expected value: <1, >=3, !=enabled
 * 	'local_value'	=>	The current value, ("Not installed")
 * 	'comment'		=>	Free form text to help resovle the problem ("Enable / Search for plugin <link>")
 * )
 *
 * @param array $dep An ElggPluginPackage dependency array
 * @return array
 * @since 1.8.0
 * @access private
 */
function _elgg_get_plugin_dependency_strings($dep) {
	$dep_system = elgg_extract('type', $dep);
	$info = elgg_extract('dep', $dep);
	$type = elgg_extract('type', $info);

	if (!$dep_system || !$info || !$type) {
		return false;
	}

	// rewrite some of these to be more readable
	$comparison = elgg_extract('comparison', $info);
	switch($comparison) {
		case 'lt':
			$comparison = '<';
			break;
		case 'gt':
			$comparison = '>';
			break;
		case 'ge':
			$comparison = '>=';
			break;
		case 'le':
			$comparison = '<=';
			break;
		default:
			//keep $comparison value intact
			break;
	}

	/*
	'requires'	'plugin oauth_lib'	<1.3	1.3		'downgrade'
	'requires'	'php setting bob'	>3		3		'change it'
	'conflicts'	'php setting'		>3		4		'change it'
	'conflicted''plugin profile'	any		1.8		'disable profile'
	'provides'	'plugin oauth_lib'	1.3		--		--
	'priority'	'before blog'		--		after	'move it'
	*/
	$strings = array();
	$strings['type'] = elgg_echo('ElggPlugin:Dependencies:' . ucwords($dep_system));

	switch ($type) {
		case 'elgg_version':
		case 'elgg_release':
			// 'Elgg Version'
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Elgg');
			$strings['expected_value'] = "$comparison {$info['version']}";
			$strings['local_value'] = $dep['value'];
			$strings['comment'] = '';
			break;

		case 'php_version':
			// 'PHP version'
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:PhpVersion');
			$strings['expected_value'] = "$comparison {$info['version']}";
			$strings['local_value'] = $dep['value'];
			$strings['comment'] = '';
			break;

		case 'php_extension':
			// PHP Extension %s [version]
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:PhpExtension', array($info['name']));
			if ($info['version']) {
				$strings['expected_value'] = "$comparison {$info['version']}";
				$strings['local_value'] = $dep['value'];
			} else {
				$strings['expected_value'] = '';
				$strings['local_value'] = '';
			}
			$strings['comment'] = '';
			break;

		case 'php_ini':
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:PhpIni', array($info['name']));
			$strings['expected_value'] = "$comparison {$info['value']}";
			$strings['local_value'] = $dep['value'];
			$strings['comment'] = '';
			break;

		case 'plugin':
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Plugin', array($info['name']));
			$expected = $info['version'] ? "$comparison {$info['version']}" : elgg_echo('any');
			$strings['expected_value'] = $expected;
			$strings['local_value'] = $dep['value'] ? $dep['value'] : '--';
			$strings['comment'] = '';
			break;

		case 'priority':
			$expected_priority = ucwords($info['priority']);
			$real_priority = ucwords($dep['value']);
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Priority');
			$strings['expected_value'] = elgg_echo("ElggPlugin:Dependencies:Priority:$expected_priority", array($info['plugin']));
			$strings['local_value'] = elgg_echo("ElggPlugin:Dependencies:Priority:$real_priority", array($info['plugin']));
			$strings['comment'] = '';
			break;
	}

	if ($dep['type'] == 'suggests') {
		if ($dep['status']) {
			$strings['comment'] = elgg_echo('ok');
		} else {
			$strings['comment'] = elgg_echo('ElggPlugin:Dependencies:Suggests:Unsatisfied');
		}
	} else {
		if ($dep['status']) {
			$strings['comment'] = elgg_echo('ok');
		} else {
			$strings['comment'] = elgg_echo('error');
		}
	}

	return $strings;
}

/**
 * Returns the ElggPlugin entity of the last plugin called.
 *
 * @return mixed ElggPlugin or false
 * @since 1.8.0
 * @access private
 * @deprecated 1.9
 */
function elgg_get_calling_plugin_entity() {
	elgg_deprecated_notice("elgg_get_calling_plugin_entity() is deprecated.", 1.9);
	$plugin_id = elgg_get_calling_plugin_id();

	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	}

	return false;
}

/**
 * Returns an array of all plugin user settings for a user.
 *
 * @param int    $user_guid  The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id  The plugin ID (Required)
 * @param bool   $return_obj Return settings as an object? This can be used to in reusable
 *                           views where the settings are passed as $vars['entity'].
 * @return array
 * @since 1.8.0
 * @see ElggPlugin::getAllUserSettings()
 */
function elgg_get_all_plugin_user_settings($user_guid = 0, $plugin_id = null, $return_obj = false) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_get_all_plugin_user_settings() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin instanceof ElggPlugin) {
		return false;
	}

	$settings = $plugin->getAllUserSettings((int)$user_guid);

	if ($settings && $return_obj) {
		$return = new stdClass;

		foreach ($settings as $k => $v) {
			$return->$k = $v;
		}

		return $return;
	} else {
		return $settings;
	}
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
 * @see ElggPlugin::setUserSetting()
 */
function elgg_set_plugin_user_setting($name, $value, $user_guid = 0, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_set_plugin_user_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->setUserSetting($name, $value, (int)$user_guid);
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
 * @see ElggPlugin::unsetUserSetting()
 */
function elgg_unset_plugin_user_setting($name, $user_guid = 0, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_unset_plugin_user_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->unsetUserSetting($name, (int)$user_guid);
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name      The name of the setting.
 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return mixed
 * @since 1.8.0
 * @see ElggPlugin::getUserSetting()
 */
function elgg_get_plugin_user_setting($name, $user_guid = 0, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_get_plugin_user_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->getUserSetting($name, (int)$user_guid);
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
 * @see ElggPlugin::setSetting()
 */
function elgg_set_plugin_setting($name, $value, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_set_plugin_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

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
 * @see ElggPlugin::getSetting()
 */
function elgg_get_plugin_setting($name, $plugin_id = null, $default = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_get_plugin_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

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
 * @see ElggPlugin::unsetSetting()
 */
function elgg_unset_plugin_setting($name, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_unset_plugin_setting() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->unsetSetting($name);
}

/**
 * Unsets all plugin settings for a plugin.
 *
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @see ElggPlugin::unsetAllSettings()
 */
function elgg_unset_all_plugin_settings($plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		elgg_deprecated_notice('elgg_unset_all_plugin_settings() requires plugin_id to be set', 1.9);
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->unsetAllSettings();
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
function elgg_get_entities_from_plugin_user_settings(array $options = array()) {
	if (!isset($options['plugin_id'])) {
		elgg_deprecated_notice("'plugin_id' is now required for elgg_get_entities_from_plugin_user_settings()", 1.9);
		$options['plugin_id'] = elgg_get_calling_plugin_id();
	}

	$singulars = array('plugin_user_setting_name', 'plugin_user_setting_value',
		'plugin_user_setting_name_value_pair');

	$options = _elgg_normalize_plural_options_array($options, $singulars);

	// rewrite plugin_user_setting_name_* to the right PS ones.
	$map = array(
		'plugin_user_setting_names' => 'private_setting_names',
		'plugin_user_setting_values' => 'private_setting_values',
		'plugin_user_setting_name_value_pairs' => 'private_setting_name_value_pairs',
		'plugin_user_setting_name_value_pairs_operator' => 'private_setting_name_value_pairs_operator',
	);

	foreach ($map as $plugin => $private) {
		if (!isset($options[$plugin])) {
			continue;
		}

		if (isset($options[$private])) {
			if (!is_array($options[$private])) {
				$options[$private] = array($options[$private]);
			}

			$options[$private] = array_merge($options[$private], $options[$plugin]);
		} else {
			$options[$private] = $options[$plugin];
		}
	}

	$prefix = _elgg_namespace_plugin_private_setting('user_setting', '', $options['plugin_id']);
	$options['private_setting_name_prefix'] = $prefix;

	return elgg_get_entities_from_private_settings($options);
}

/**
 * Runs unit tests for plugin API.
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_plugins_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCorePluginsAPITest.php';
	return $value;
}

/**
 * Checks on deactivate plugin event if disabling it won't create unmet dependencies and blocks disable in such case.
 *
 * @param string $event  deactivate
 * @param string $type   plugin
 * @param array  $params Parameters array containing entry with ELggPlugin instance under 'plugin_entity' key
 * @return bool  false to block plugin deactivation action
 *
 * @access private
 */
function _plugins_deactivate_dependency_check($event, $type, $params) {
	$plugin_id = $params['plugin_entity']->getManifest()->getPluginID();
	$plugin_name = $params['plugin_entity']->getManifest()->getName();

	$active_plugins = elgg_get_plugins();

	$dependents = array();
	foreach ($active_plugins as $plugin) {
		$manifest = $plugin->getManifest();
		$requires = $manifest->getRequires();

		foreach ($requires as $required) {
			if ($required['type'] == 'plugin' && $required['name'] == $plugin_id) {
				// there are active dependents
				$dependents[$manifest->getPluginID()] = $plugin;
			}
		}
	}

	if ($dependents) {
		$list = '<ul>';
		// construct error message and prevent disabling
		foreach ($dependents as $dependent) {
			$list .= '<li>' . $dependent->getManifest()->getName() . '</li>';
		}
		$list .= '</ul>';

		register_error(elgg_echo('ElggPlugin:Dependencies:ActiveDependent', array($plugin_name, $list)));

		return false;
	}
}

/**
 * Initialize the plugin system
 *
 * @return void
 * @access private
 */
function _elgg_plugins_init() {

	if (elgg_is_admin_logged_in()) {
		elgg_register_ajax_view('object/plugin/full');
	}

	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_plugins_test');

	// note - plugins are booted by the time this handler is registered
	// deactivation due to error may have already occurred
	elgg_register_event_handler('deactivate', 'plugin', '_plugins_deactivate_dependency_check');

	elgg_register_action("plugins/settings/save", '', 'admin');
	elgg_register_action("plugins/usersettings/save");

	elgg_register_action('admin/plugins/activate', '', 'admin');
	elgg_register_action('admin/plugins/deactivate', '', 'admin');
	elgg_register_action('admin/plugins/activate_all', '', 'admin');
	elgg_register_action('admin/plugins/deactivate_all', '', 'admin');

	elgg_register_action('admin/plugins/set_priority', '', 'admin');

	elgg_register_library('elgg:markdown', elgg_get_root_path() . 'vendors/markdown/markdown.php');
}

elgg_register_event_handler('init', 'system', '_elgg_plugins_init');
