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
 * Returns a list of plugin IDs (dir names) from a dir.
 *
 * @param string $dir A dir to scan for plugins. Defaults to config's plugins_path.
 *
 * @return array
 */
function elgg_get_plugin_ids_in_dir($dir = null) {
	if (!$dir) {
		$dir = elgg_get_plugin_path();
	}

	$plugin_idss = array();
	$handle = opendir($dir);

	if ($handle) {
		while ($plugin_id = readdir($handle)) {
			// must be directory and not begin with a .
			if (substr($plugin_id, 0, 1) !== '.' && is_dir($dir . $plugin_id)) {
				$plugin_ids[] = $plugin_id;
			}
		}
	}

	sort($plugin_ids);

	return $plugin_ids;
}

/**
 * Discovers plugins in the plugins_path setting and creates ElggPlugin
 * entities for them if they don't exist.  If there are plugins with entities
 * but not actual files, will disable the ElggPlugin entities and mark as inactive.
 * The ElggPlugin object holds config data, so don't delete.
 *
 * @todo Crappy name?
 * @return bool
 */
function elgg_generate_plugin_entities() {
	$site = get_config('site');
	$dir = elgg_get_plugin_path();

	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'limit' => ELGG_ENTITIES_NO_VALUE
	);

	$old_ia = elgg_set_ignore_access(true);
	$old_access = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	$known_plugins = elgg_get_entities_from_relationship($options);

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

	$physical_plugins = elgg_get_plugin_ids_in_dir($dir);

	if (!$physical_plugins) {
		return false;
	}

	$new_plugin_priority = elgg_get_max_plugin_priority() + 1;

	// check real plugins against known ones
	foreach ($physical_plugins as $plugin_id) {
		// is this already in the db?
		if (array_key_exists($plugin_id, $id_map)) {
			$index = $id_map[$plugin_id];
			$plugin = $known_plugins[$index];
			// was this plugin deleted and its entity disabled?
			if ($plugin->enabled != 'yes') {
				$plugin->enable();
				$plugin->deactivate();
				$plugin->setPriority($new_plugin_priority);

				$new_plugin_priority++;
			}

			// remove from the list of plugins to disable
			unset($known_plugins[$index]);
		} else {
			// add new plugins
			$plugin = new ElggPlugin($plugin_id);
			$plugin->save();
			$plugin->setPriority($new_plugin_priority);

			$new_plugin_priority++;
		}
	}

	// everything remaining in $known_plugins needs to be disabled
	// because they are entities, but their dirs were removed.
	// don't delete the entities because they hold settings.
	foreach ($known_plugins as $plugin) {
		if ($plugin->isActive()) {
			$plugin->deactivate();
			// remove the priority.
			$name = elgg_namespace_plugin_private_setting('internal', 'priority');
			remove_private_setting($plugin->guid, $name);
			$plugin->disable();
		}
	}

	access_show_hidden_entities($old_access);
	elgg_set_ignore_access($old_ia);

	elgg_reindex_plugin_priorities();

	return true;
}

/**
 * Returns an ElggPlugin object with the path $path.
 *
 * @param string $id The id (dir name) of the plugin. NOT the guid.
 * @return mixed ElggPlugin or false.
 */
function elgg_get_plugin_from_id($id) {
	$id = sanitize_string($id);
	$db_prefix = get_config('dbprefix');

	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'joins' => array("JOIN {$db_prefix}objects_entity oe on oe.guid = e.guid"),
		'wheres' => array("oe.title = '$id'"),
		'limit' => 1
	);

	$plugins = elgg_get_entities($options);

	if ($plugins) {
		return $plugins[0];
	}

	return false;
}

/**
 * Returns if a plugin exists in the system.
 *
 * @warning This checks only plugins that are registered in the system!
 * If the plugin cache is outdated, be sure to regenerate it with
 * {@link elgg_generate_plugin_objects()} first.
 *
 * @param string $id The plugin ID.
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
 */
function elgg_get_max_plugin_priority() {
	$db_prefix = get_config('dbprefix');
	$priority = elgg_namespace_plugin_private_setting('internal', 'priority');
	$plugin_subtype = get_subtype_id('object', 'plugin');

	$q = "SELECT MAX(CAST(ps.value AS unsigned)) as max
		FROM {$db_prefix}entities e, {$db_prefix}private_settings ps
		WHERE ps.name = '$priority'
		AND ps.entity_guid = e.guid
		AND e.type = 'object' and e.subtype = $plugin_subtype";

	$data = get_data($q);
	if ($data) {
		return $data[0]->max;
	}

	// can't have a priority of 0.
	return 1;
}

/**
 * Loads all active plugins in the order specified in the tool admin panel.
 *
 * @note This is called on every page load and includes additional checking that plugins
 * are fit to be loaded.  If a plugin is active and problematic, it will be disabled
 * and a visible error emitted.
 *
 * @return bool
 */
function elgg_load_plugins() {
	global $CONFIG;

	$plugins_path = elgg_get_plugin_path();
	$start_flags =	ELGG_PLUGIN_INCLUDE_START
					| ELGG_PLUGIN_REGISTER_VIEWS
					| ELGG_PLUGIN_REGISTER_LANGUAGES
					| ELGG_PLUGIN_REGISTER_CLASSES;

	if (!$plugins_path) {
		return false;
	}

	// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
	if (file_exists("$plugins_path/disabled")) {
		return false;
	}

	// Load view caches if available
	$cached_view_paths = elgg_filepath_cache_load('views');
	$cached_view_types = elgg_filepath_cache_load('view_types');
	$cached_view_info = is_string($cached_view_paths) && is_string($cached_view_types);

	if ($cached_view_info) {
		$CONFIG->views = unserialize($cached_view_paths);
		$CONFIG->view_types = unserialize($cached_view_types);

		// don't need to register views
		$start_flags = $start_flags & ~ELGG_PLUGIN_REGISTER_VIEWS;
	}

	$return = true;
	$plugins = elgg_get_plugins('active');
	if ($plugins) {
		foreach ($plugins as $plugin) {
			// check if plugin can be started and try to start it.
			// if anything is bad, disable it and emit a message.
			if (!$plugin->isValid()) {
				$plugin->deactivate();
				$msg = elgg_echo('PluginException:MisconfiguredPlugin', array($plugin->getID(), $plugin->guid));
				register_error($msg);
				$return = false;

				continue;
			}

			try {
				$plugin->start($start_flags);
			} catch (Exception $e) {
				$plugin->deactivate();
				$msg = elgg_echo('PluginException:CannotStart',
								array($plugin->getID(), $plugin->guid, $e->getMessage()));
				register_error($msg);
				$return = false;

				continue;
			}
		}
	}

	// Cache results
	if (!$cached_view_info) {
		elgg_filepath_cache_save('views', serialize($CONFIG->views));
		elgg_filepath_cache_save('view_types', serialize($CONFIG->view_types));
	}

	return $return;
}

/**
 * Returns an ordered list of plugins
 *
 * @param string $status          The status of the plugins. active, inactive, or all.
 * @param bool   $include_deleted Include physically deleted (and so inactive and disabled) plugins?
 * @param mixed  $site_guid       Optional site guid
 * @return array
 */
function elgg_get_plugins($status = 'active', $include_deleted = false, $site_guid = NULL) {
	$db_prefix = get_config('dbprefix');
	$priority = elgg_namespace_plugin_private_setting('internal', 'priority');

	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	// grab plugins
	$options = array(
		'type' => 'object',
		'subtype' => 'plugin',
		'limit' => ELGG_ENTITIES_NO_VALUE,
		'joins' => array("JOIN {$db_prefix}private_settings ps on ps.entity_guid = e.guid"),
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

	if ($include_deleted) {
		$old_id = elgg_set_ignore_access(true);
	}

	$plugins = elgg_get_entities_from_relationship($options);

	if ($include_deleted) {
		elgg_set_ignore_access($old_ia);
	}

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
 */
function elgg_set_plugin_priorities(array $order) {
	$name = elgg_namespace_plugin_private_setting('internal', 'priority');

	$plugins = elgg_get_plugins('any', true);
	if (!$plugins) {
		return false;
	}

	$return = true;

	// reindex to get standard counting. no need to increment by 10.
	// though we do start with 1
	$order = array_values($order);

	foreach ($plugins as $plugin) {
		$plugin_id = $plugin->getID();

		if (!in_array($plugin_id, $order)) {
			$missing_plugins[] = $plugin;
			continue;
		}

		$priority = array_search($plugin_id, $order) + 1;

		if (!$plugin->set($name, $priority)) {
			$return = false;
			break;
		}
	}

	// set the missing plugins priorities
	if ($return && $missing_plugins) {
		if (!$priority) {
			$priority = 0;
		}
		foreach ($missing_plugins as $plugin) {
			$priority++;
			if (!$plugin->set($name, $priority)) {
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
 */
function elgg_reindex_plugin_priorities() {
	return elgg_set_plugin_priorities(array());
}

/**
 * Returns a list of plugins to load, in the order that they should be loaded.
 *
 * @deprecated 1.8
 *
 * @return array List of plugins
 */
function get_plugin_list() {
	elgg_deprecated_notice('get_plugin_list() is deprecated by elgg_get_plugin_ids_in_dir() or elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins('any');

	$list = array();
	if ($plugins) {
		foreach ($plugins as $i => $plugin) {
			// in <=1.7 this returned indexed by multiples of 10.
			// uh...sure...why not.
			$index = ($i + 1) * 10;
			$list[$index] = $plugin->getID();
		}
	}

	return $list;
}

/**
 * Regenerates the list of known plugins and saves it to the current site
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_view_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param array $pluginorder Optionally, a list of existing plugins and their orders
 *
 * @return array The new list of plugins and their orders
 */
function regenerate_plugin_list($pluginorder = FALSE) {
	$msg = 'regenerate_plugin_list() is (sorta) deprecated by elgg_generate_plugin_entities() and'
			. ' elgg_set_plugin_priorities().';
	elgg_deprecated_notice($msg, 1.8);

	// they're probably trying to set it?
	if ($pluginorder) {
		if (elgg_generate_plugin_entities()) {
			// sort the plugins by the index numerically since we used
			// weird indexes in the old system.
			ksort($pluginorder, SORT_NUMERIC);
			return elgg_set_plugin_priorities($pluginorder);
		}
		return false;
	} else {
		// they're probably trying to regenerate from disk?
		return elgg_generate_plugin_entities();
	}
}


/**
 * Loads plugins
 *
 * @deprecate 1.8
 *
 * @return bool
 */
function load_plugins() {
	elgg_deprecated_notice('load_plugins() is deprecated by elgg_load_plugins()', 1.8);

	return elgg_load_plugins();
}


/**
 * Namespaces a string to be used as a private setting for a plugin.
 *
 * @param string $type The type of value: user_setting or internal.
 * @param string $name The name to namespace.
 * @param string $id   The plugin's ID to namespace with.  Required for user_setting.
 * @return string
 */
function elgg_namespace_plugin_private_setting($type, $name, $id = null) {
	switch ($type) {
//		case 'setting':
//			$name = ELGG_PLUGIN_SETTING_PREFIX . $name;
//			break;

		case 'user_setting':
			if (!$id) {
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
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, this would return foo_bar.
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @since 1.8
 *
 * @return string|false Plugin name, or false if no plugin name was called
 */
function elgg_get_calling_plugin_id($mainfilename = false) {
	if (!$mainfilename) {
		if ($backtrace = debug_backtrace()) {
			foreach ($backtrace as $step) {
				$file = $step['file'];
				$file = str_replace("\\", "/", $file);
				$file = str_replace("//", "/", $file);
				if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\/start\.php$/", $file, $matches)) {
					return $matches[1];
				}
			}
		}
	} else {
		if (preg_match("/pg\/([a-zA-Z0-9\-\_]*)\//", $_SERVER['REQUEST_URI'], $matches)) {
			return $matches[1];
		} else {
			$file = $_SERVER["SCRIPT_NAME"];
			$file = str_replace("\\", "/", $file);
			$file = str_replace("//", "/", $file);
			if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\//", $file, $matches)) {
				return $matches[1];
			}
		}
	}
	return false;
}

/**
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, get_plugin_name would return foo_bar.
 *
 * @deprecated 1.8
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @return string|false Plugin name, or false if no plugin name was called
 */
function get_plugin_name($mainfilename = false) {
	elgg_deprecated_notice('get_plugin_name() is deprecated by elgg_get_calling_plugin_id()', 1.8);

	return elgg_get_calling_plugin_id($mainfilename);
}

/**
 * Load and parse a plugin manifest from a plugin XML file.
 *
 * @example plugins/manifest.xml Example 1.8-style manifest file.
 *
 * @deprecated 1.8
 *
 * @param string $plugin Plugin name.
 * @return array of values
 */
function load_plugin_manifest($plugin) {
	elgg_deprecated_notice('load_plugin_manifest() is deprecated by ElggPlugin->getManifest()', 1.8);

	$xml_file = elgg_get_plugin_path() . "$plugin/manifest.xml";

	try {
		$manifest = new ElggPluginManifest($xml_file, $plugin);
	} catch(Exception $e) {
		return false;
	}

	return $manifest->getManifest();
}

/**
 * This function checks a plugin manifest 'elgg_version' value against the current install
 * returning TRUE if the elgg_version is >= the current install's version.
 *
 * @deprecated 1.8
 *
 * @param string $manifest_elgg_version_string The build version (eg 2009010201).
 * @return bool
 */
function check_plugin_compatibility($manifest_elgg_version_string) {
	elgg_deprecated_notice('check_plugin_compatibility() is deprecated by ElggPlugin->canActivate()', 1.8);

	$version = get_version();

	if (strpos($manifest_elgg_version_string, '.') === false) {
		// Using version
		$req_version = (int)$manifest_elgg_version_string;

		return ($version >= $req_version);
	}

	return false;
}

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
 * @since 1.8
 */
function elgg_get_plugins_provides($type = null, $name = null) {
	static $provides = null;
	$active_plugins = get_installed_plugins('enabled');

	if (!isset($provides)) {
		$provides = array();

		foreach ($active_plugins as $plugin_id => $plugin_info) {
			// @todo remove this when fully converted to ElggPluginPackage.
			$package = new ElggPluginPackage($plugin_id);

			if ($plugin_provides = $package->getManifest()->getProvides()) {
				foreach ($plugin_provides as $provided) {
					$provides[$provided['type']][$provided['name']] = array(
						'version' => $provided['version'],
						'provided_by' => $plugin_id
					);
				}
			}
		}
	}

	if ($type && $name) {
		if (isset($provides[$type][$name])) {
			return $provides[$type][$name];
		} else {
			return false;
		}
	} elseif ($type) {
		if (isset($provides[$type])) {
			return $provides[$type];
		} else {
			return false;
		}
	}

	return $provides;
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
 * @return bool
 * @since 1.8
 */
function elgg_check_plugins_provides($type, $name, $version = null, $comparison = 'ge') {
	if (!$provided = elgg_get_plugins_provides($type, $name)) {
		return false;
	}

	if ($provided) {
		if ($version) {
			return version_compare($provided['version'], $version, $comparison);
		} else {
			return true;
		}
	}
}

/**
 * Shorthand function for finding the plugin settings.
 *
 * @deprecated 1.8
 *
 * @param string $plugin_id Optional plugin id, if not specified
 *                          then it is detected from where you are calling.
 *
 * @return mixed
 */
function find_plugin_settings($plugin_id = null) {
	elgg_deprecated_notice('find_plugin_setting() is deprecated by elgg_get_calling_plugin_entity() or elgg_get_plugin_from_id()', 1.8);
	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	} else {
		return elgg_get_calling_plugin_entity();
	}
}

/**
 * Returns the ElggPlugin entity of the last plugin called.
 *
 * @return mixed ElggPlugin or false
 * @since 1.8
 */
function elgg_get_calling_plugin_entity() {
	$plugin_id = elgg_get_calling_plugin_id();

	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	}

	return false;
}

/**
 * Find the plugin settings for a user.
 *
 * @param string $plugin_id Plugin name.
 * @param int    $user_guid The guid who's settings to retrieve.
 *
 * @return array of settings in an associative array minus prefix.
 */
function find_plugin_usersettings($plugin_id = null, $user_guid = 0) {
	$plugin_id = sanitise_string($plugin_id);
	$user_guid = (int)$user_guid;
	$db_prefix = get_config('db_prefix');
	$ps_prefix = elgg_namespace_plugin_private_setting('user_setting', "$plugin_id:");
	$ps_prefix_len = strlen($ps_prefix);

	if (!$plugin_id) {
		$plugin_id = elgg_get_calling_plugin_id();
	}

	if ($user_guid == 0) {
		$user_guid = get_loggedin_userid();
	}

	// Get private settings for user
	$q = "SELECT * FROM {$db_prefix}private_settings
		WHERE entity_guid = $user_guid
		AND name LIKE '$ps_prefix$plugin_id'";

	$private_settings = get_data($q);
	if ($private_settings) {
		$return = new stdClass;

		foreach ($private_settings as $setting) {
			$name = substr($setting->name, $ps_prefix_len);
			$value = $setting->value;

			// @todo why?
			if (strpos($key, $ps_prefix) === 0) {
				$return->$name = $value;
			}
		}

		return $return;
	}

	return false;
}

/**
 * Set a user specific setting for a plugin.
 *
 * @param string $name      The name - note, can't be "title".
 * @param mixed  $value     The value.
 * @param int    $user_guid Optional user.
 * @param string $plugin_id Optional plugin name, if not specified then it
 *                          is detected from where you are calling from.
 *
 * @return bool
 */
function set_plugin_usersetting($name, $value, $user_guid = 0, $plugin_id = "") {
	$plugin_id = sanitise_string($plugin_id);
	$user_guid = (int)$user_guid;
	$name = sanitise_string($name);

	if (!$plugin_id) {
		$plugin_id = elgg_get_calling_plugin_id();
	}

	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$name = elgg_namespace_plugin_private_setting('user_setting', "$plugin_id:$name");

		// Hook to validate setting
		$value = elgg_trigger_plugin_hook('plugin:usersetting', 'user', array(
			'user' => $user,
			'plugin' => $plugin_id,
			'name' => $name,
			'value' => $value
		), $value);

		return set_private_setting($user->guid, $name, $value);
	}

	return false;
}

/**
 * Clears a user-specific plugin setting
 *
 * @param str $name      Name of the plugin setting
 * @param int $user_guid Defaults to logged in user
 * @param str $plugin_id Defaults to contextual plugin name
 *
 * @return bool Success
 */
function clear_plugin_usersetting($name, $user_guid = 0, $plugin_id = '') {
	$plugin_id = sanitise_string($plugin_id);
	$name = sanitise_string($name);

	if (!$plugin_id) {
		$plugin_id = elgg_get_calling_plugin_id();
	}

	$user = get_entity((int) $user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$prefix = elgg_namespace_plugin_private_setting('user_setting', "$plugin_id:$name");

		return remove_private_setting($user->getGUID(), $prefix);
	}

	return FALSE;
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name      The name.
 * @param int    $user_guid Guid of owning user
 * @param string $plugin_id Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return mixed
 */
function get_plugin_usersetting($name, $user_guid = 0, $plugin_id = "") {
	$plugin_id = sanitise_string($plugin_id);
	$user_guid = (int)$user_guid;
	$name = sanitise_string($name);

	if (!$plugin_id) {
		$plugin_id = elgg_get_calling_plugin_id();
	}

	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$name = elgg_namespace_plugin_private_setting('user_setting', "$plugin_id:$name");
		return get_private_setting($user->guid, $name);
	}

	return false;
}

/**
 * Set a setting for a plugin.
 *
 * @param string $name      The name - note, can't be "title".
 * @param mixed  $value     The value.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return int|false
 */
function set_plugin_setting($name, $value, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
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
 * @param string $name      The name.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return mixed
 */
function get_plugin_setting($name, $plugin_id = "") {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->getSetting($name);
}

/**
 * Clear a plugin setting.
 *
 * @param string $name      The name.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 */
function clear_plugin_setting($name, $plugin_id = "") {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->removeSetting($name);
}

/**
 * Clear all plugin settings.
 *
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 * @since 1.7.0
 */
function clear_all_plugin_settings($plugin_id = "") {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if ($plugin) {
		$plugin->removeAllSettings();
	}

	return false;
}

/**
 * Return an array of installed plugins.
 *
 * @deprecated 1.8
 *
 * @param string $status any|enabled|disabled
 * @return array
 */
function get_installed_plugins($status = 'all') {
	global $CONFIG;

	elgg_deprecated_notice('get_installed_plugins() was deprecated by elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins($status);

	if (!$plugins) {
		return array();
	}

	$installed_plugins = array();

	foreach ($plugins as $plugin) {
		if (!$plugin->isValid()) {
			continue;
		}

		$installed_plugins[$plugin->getID()] = array(
			'active' => $plugin->isActive(),
			'manifest' => $plugin->manifest->getManifest()
		);
	}

	return $installed_plugins;
}

/**
 * Enable a plugin for a site (default current site)
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_view_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return array
 * @throws InvalidClassException
 */
function enable_plugin($plugin, $site_guid = null) {
	elgg_deprecated_notice('enable_plugin() was deprecated by ElggPlugin->activate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	if (!$plugin->canActivate($site_guid)) {
		return false;
	}

	return $plugin->activate($site_guid);
}

/**
 * Disable a plugin for a site (default current site)
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_view_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 * @throws InvalidClassException
 */
function disable_plugin($plugin, $site_guid = 0) {
	elgg_deprecated_notice('disable_plugin() was deprecated by ElggPlugin->deactivate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	return $plugin->deactivate($site_guid);
}

/**
 * Return whether a plugin is enabled or not.
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 */
function is_plugin_enabled($plugin, $site_guid = 0) {
	elgg_deprecated_notice('is_plugin_enabled() was deprecated by ElggPlugin->isActive()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	return $plugin->isActive($site_guid);
}

/**
 * Register object, plugin entities as ElggPlugin classes
 *
 *  @return void
 */
function plugin_run_once() {
	add_subtype("object", "plugin", "ElggPlugin");
}


/**
 * Runs unit tests for the entity objects.
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function plugins_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/plugins.php';
	return $value;
}

/**
 * Initialise the file modules.
 * Listens to system boot and registers any appropriate file types and classes
 *
 * @return void
 */
function plugin_init() {
	run_function_once("plugin_run_once");

	elgg_register_plugin_hook_handler('unit_test', 'system', 'plugins_test');

	elgg_register_action("plugins/settings/save", '', 'admin');
	elgg_register_action("plugins/usersettings/save");

	elgg_register_action('admin/plugins/enable', '', 'admin');
	elgg_register_action('admin/plugins/disable', '', 'admin');
	elgg_register_action('admin/plugins/enableall', '', 'admin');
	elgg_register_action('admin/plugins/disableall', '', 'admin');

	elgg_register_action('admin/plugins/reorder', '', 'admin');
}

elgg_register_event_handler('init', 'system', 'plugin_init');