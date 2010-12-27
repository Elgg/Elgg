<?php
/**
 * Elgg plugins library
 * Contains functions for managing plugins
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

/// Cache enabled plugins per page
$ENABLED_PLUGINS_CACHE = NULL;

/**
 * Returns a list of plugins to load, in the order that they should be loaded.
 *
 * @return array List of plugins
 */
function get_plugin_list() {
	global $CONFIG;

	if (!empty($CONFIG->pluginlistcache)) {
		return $CONFIG->pluginlistcache;
	}

	if ($site = get_entity($CONFIG->site_guid)) {
		$pluginorder = $site->pluginorder;
		if (!empty($pluginorder)) {
			$plugins = unserialize($pluginorder);

			$CONFIG->pluginlistcache = $plugins;
			return $plugins;
		} else {
			// this only runs on install, otherwise uses serialized plugin order
			$plugins = array();

			if ($handle = opendir($CONFIG->pluginspath)) {
				while ($mod = readdir($handle)) {
					// must be directory and not begin with a .
					if (substr($mod, 0, 1) !== '.' && is_dir($CONFIG->pluginspath . "/" . $mod)) {
						$plugins[] = $mod;
					}
				}
			}

			sort($plugins);

			$CONFIG->pluginlistcache = $plugins;
			return $plugins;
		}
	}

	return false;
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
 * @param array $pluginorder Optionally, a list of existing plugins and their orders
 *
 * @return array The new list of plugins and their orders
 */
function regenerate_plugin_list($pluginorder = FALSE) {
	global $CONFIG;

	$CONFIG->pluginlistcache = NULL;

	if ($site = get_entity($CONFIG->site_guid)) {
		if (empty($pluginorder)) {
			$pluginorder = $site->pluginorder;
			$pluginorder = unserialize($pluginorder);
		} else {
			ksort($pluginorder);
		}

		if (empty($pluginorder)) {
			$pluginorder = array();
		}

		$max = 0;
		if (sizeof($pluginorder)) {
			foreach ($pluginorder as $key => $plugin) {
				if (is_dir($CONFIG->pluginspath . "/" . $plugin)) {
					if ($key > $max) {
						$max = $key;
					}
				} else {
					unset($pluginorder[$key]);
				}
			}
		}
		// Add new plugins to the end
		if ($handle = opendir($CONFIG->pluginspath)) {
			while ($mod = readdir($handle)) {
				// must be directory and not begin with a .
				if (substr($mod, 0, 1) !== '.' && is_dir($CONFIG->pluginspath . "/" . $mod)) {
					if (!in_array($mod, $pluginorder)) {
						$max = $max + 10;
						$pluginorder[$max] = $mod;
					}
				}
			}
		}

		ksort($pluginorder);

		// Now reorder the keys ..
		$key = 10;
		$plugins = array();
		if (sizeof($pluginorder)) {
			foreach ($pluginorder as $plugin) {
				$plugins[$key] = $plugin;
				$key = $key + 10;
			}
		}

		$plugins = serialize($plugins);

		$site->pluginorder = $plugins;

		return $plugins;
	}

	return FALSE;
}


/**
 * For now, loads plugins directly
 *
 * @todo Add proper plugin handler that launches plugins in an
 * admin-defined order and activates them on admin request
 *
 * @return void
 */
function load_plugins() {
	global $CONFIG;

	if (empty($CONFIG->pluginspath)) {
		return;
	}

	// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
	if (file_exists($CONFIG->pluginspath . "disabled")) {
		return;
	}
	
	// See if we have cached values for things
	$cached_view_paths = elgg_filepath_cache_load('views');
	$cached_view_types = elgg_filepath_cache_load('view_types');
	$cached_view_info = is_string($cached_view_paths) && is_string($cached_view_types);
	if ($cached_view_info) {
		$CONFIG->views = unserialize($cached_view_paths);
		$CONFIG->view_types = unserialize($cached_view_types);
	}

	$plugins = get_plugin_list();

	if (sizeof($plugins)) {
		foreach ($plugins as $mod) {
			if (is_plugin_enabled($mod)) {
				if (file_exists($CONFIG->pluginspath . $mod)) {
					if (!include($CONFIG->pluginspath . $mod . "/start.php")) {
						// automatically disable the bad plugin
						disable_plugin($mod);

						// register error rather than rendering the site unusable with exception
						register_error(sprintf(elgg_echo('PluginException:MisconfiguredPlugin'), $mod));

						// continue loading remaining plugins
						continue;
					}

					if (!$cached_view_info) {
						$view_dir = $CONFIG->pluginspath . $mod . '/views/';

						if (is_dir($view_dir) && ($handle = opendir($view_dir))) {
							while (FALSE !== ($view_type = readdir($handle))) {
								$view_type_dir = $view_dir . $view_type;

								if ('.' !== substr($view_type, 0, 1) && is_dir($view_type_dir)) {
									if (autoregister_views('', $view_type_dir, $view_dir, $view_type)) {
										// add the valid view type.
										if (!in_array($view_type, $CONFIG->view_types)) {
											$CONFIG->view_types[] = $view_type;
										}
									}
								}
							}
						}
					}

					if (is_dir($CONFIG->pluginspath . $mod . "/languages")) {
						register_translations($CONFIG->pluginspath . $mod . "/languages/");
					}
				}
			}
		}
	}

	// Cache results
	if (!$cached_view_info) {
		elgg_filepath_cache_save('views', serialize($CONFIG->views));
		elgg_filepath_cache_save('view_types', serialize($CONFIG->view_types));
	}
}

/**
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, get_plugin_name would return foo_bar.
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @return string|false Plugin name, or false if no plugin name was called
 */
function get_plugin_name($mainfilename = false) {
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
 * Load and parse a plugin manifest from a plugin XML file.
 *
 * Example file:
 *
 *	<plugin_manifest>
 *		<!-- Basic information -->
 *		<field key="name" value="My Plugin" />
 *		<field key="description" value="My Plugin's concise description" />
 *		<field key="version" value="1.0" />
 *		<field key="category" value="theme" />
 *		<field key="category" value="bundled" />
 *		<field key="screenshot" value="path/relative/to/my_plugin.jpg" />
 *		<field key="screenshot" value="path/relative/to/my_plugin_2.jpg" />
 *
 *		<field key="author" value="Curverider Ltd" />
 *		<field key="website" value="http://www.elgg.org/" />
 *		<field key="copyright" value="(C) Curverider 2008-2010" />
 *		<field key="licence" value="GNU Public License version 2" />
 *	</plugin_manifest>
 *
 * @param string $plugin Plugin name.
 *
 * @return array of values
 */
function load_plugin_manifest($plugin) {
	$xml_file = get_config('pluginspath') . "$plugin/manifest.xml";

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
 * @param string $manifest_elgg_version_string The build version (eg 2009010201).
 *
 * @return bool
 */
function check_plugin_compatibility($manifest_elgg_version_string) {
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
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return mixed
 */
function find_plugin_settings($plugin_name = "") {
	$options = array('type' => 'object', 'subtype' => 'plugin', 'limit' => 9999);
	$plugins = elgg_get_entities($options);
	$plugin_name = sanitise_string($plugin_name);
	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}

	if ($plugins) {
		foreach ($plugins as $plugin) {
			if (strcmp($plugin->title, $plugin_name) == 0) {
				return $plugin;
			}
		}
	}

	return false;
}

/**
 * Find the plugin settings for a user.
 *
 * @param string $plugin_name Plugin name.
 * @param int    $user_guid   The guid who's settings to retrieve.
 *
 * @return array of settings in an associative array minus prefix.
 */
function find_plugin_usersettings($plugin_name = "", $user_guid = 0) {
	$plugin_name = sanitise_string($plugin_name);
	$user_guid = (int)$user_guid;

	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}

	if ($user_guid == 0) {
		$user_guid = get_loggedin_userid();
	}

	// Get metadata for user
	$all_metadata = get_all_private_settings($user_guid); //get_metadata_for_entity($user_guid);
	if ($all_metadata) {
		$prefix = "plugin:settings:$plugin_name:";
		$return = new stdClass;

		foreach ($all_metadata as $key => $meta) {
			$name = substr($key, strlen($prefix));
			$value = $meta;

			if (strpos($key, $prefix) === 0) {
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
 * @param string $name        The name - note, can't be "title".
 * @param mixed  $value       The value.
 * @param int    $user_guid   Optional user.
 * @param string $plugin_name Optional plugin name, if not specified then it
 *                            is detected from where you are calling from.
 *
 * @return bool
 */
function set_plugin_usersetting($name, $value, $user_guid = 0, $plugin_name = "") {
	$plugin_name = sanitise_string($plugin_name);
	$user_guid = (int)$user_guid;
	$name = sanitise_string($name);

	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}

	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$prefix = "plugin:settings:$plugin_name:$name";
		//$user->$prefix = $value;
		//$user->save();

		// Hook to validate setting
		$value = elgg_trigger_plugin_hook('plugin:usersetting', 'user', array(
			'user' => $user,
			'plugin' => $plugin_name,
			'name' => $name,
			'value' => $value
		), $value);

		return set_private_setting($user->guid, $prefix, $value);
	}

	return false;
}

/**
 * Clears a user-specific plugin setting
 *
 * @param str $name        Name of the plugin setting
 * @param int $user_guid   Defaults to logged in user
 * @param str $plugin_name Defaults to contextual plugin name
 *
 * @return bool Success
 */
function clear_plugin_usersetting($name, $user_guid = 0, $plugin_name = '') {
	$plugin_name = sanitise_string($plugin_name);
	$name = sanitise_string($name);

	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}

	$user = get_entity((int) $user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$prefix = "plugin:settings:$plugin_name:$name";

		return remove_private_setting($user->getGUID(), $prefix);
	}

	return FALSE;
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name        The name.
 * @param int    $user_guid   Guid of owning user
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return mixed
 */
function get_plugin_usersetting($name, $user_guid = 0, $plugin_name = "") {
	$plugin_name = sanitise_string($plugin_name);
	$user_guid = (int)$user_guid;
	$name = sanitise_string($name);

	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}

	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$prefix = "plugin:settings:$plugin_name:$name";
		return get_private_setting($user->guid, $prefix);
	}

	return false;
}

/**
 * Set a setting for a plugin.
 *
 * @param string $name        The name - note, can't be "title".
 * @param mixed  $value       The value.
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return int|false
 */
function set_plugin_setting($name, $value, $plugin_name = "") {
	if (!$plugin_name) {
		$plugin_name = get_plugin_name();
	}
	$plugin = find_plugin_settings($plugin_name);

	if (!$plugin) {
		$plugin = new ElggPlugin();
	}

	if ($name != 'title') {
		// Hook to validate setting
		$value = elgg_trigger_plugin_hook('plugin:setting', 'plugin', array(
			'plugin' => $plugin_name,
			'name' => $name,
			'value' => $value
		), $value);

		$plugin->title = $plugin_name;
		$plugin->access_id = ACCESS_PUBLIC;
		$plugin->save();
		$plugin->$name = $value;

		return $plugin->getGUID();
	}

	return false;
}

/**
 * Get setting for a plugin.
 *
 * @param string $name        The name.
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return mixed
 */
function get_plugin_setting($name, $plugin_name = "") {
	$plugin = find_plugin_settings($plugin_name);

	if ($plugin) {
		return $plugin->$name;
	}

	return false;
}

/**
 * Clear a plugin setting.
 *
 * @param string $name        The name.
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return bool
 */
function clear_plugin_setting($name, $plugin_name = "") {
	$plugin = find_plugin_settings($plugin_name);

	if ($plugin) {
		return remove_private_setting($plugin->guid, $name);
	}

	return FALSE;
}

/**
 * Clear all plugin settings.
 *
 * @param string $plugin_name Optional plugin name, if not specified
 *                            then it is detected from where you are calling from.
 *
 * @return bool
 * @since 1.7.0
 */
function clear_all_plugin_settings($plugin_name = "") {
	$plugin = find_plugin_settings($plugin_name);

	if ($plugin) {
		return remove_all_private_settings($plugin->guid);
	}

	return FALSE;
}

/**
 * Return an array of installed plugins.
 *
 * @param string $status any|enabled|disabled
 * @return array
 */
function get_installed_plugins($status = 'any') {
	global $CONFIG;

	$installed_plugins = array();

	if (!empty($CONFIG->pluginspath)) {
		$plugins = get_plugin_list();

		foreach ($plugins as $mod) {
			// require manifest.
			if (!$manifest = load_plugin_manifest($mod)) {
				continue;
			}

			$enabled = is_plugin_enabled($mod);

			switch ($status) {
				case 'enabled':
					if ($enabled != true) {
						continue 2;
					}
					break;

				case 'disabled':
					if ($enabled == true) {
						continue 2;
					}
					break;

				case 'any':
				default:
					break;
			}

			$installed_plugins[$mod] = array();
			$installed_plugins[$mod]['active'] = $enabled;
			$installed_plugins[$mod]['manifest'] = $manifest;
		}
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
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return array
 * @throws InvalidClassException
 */
function enable_plugin($plugin, $site_guid = 0) {
	global $CONFIG, $ENABLED_PLUGINS_CACHE;

	$plugin = sanitise_string($plugin);
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$site = get_entity($site_guid);
	if (!($site instanceof ElggSite)) {
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($site_guid, "ElggSite"));
		throw new InvalidClassException($msg);
	}

	if (!$plugin_info = load_plugin_manifest($plugin)) {
		return FALSE;
	}

	// getMetadata() doesn't return an array if only one plugin is enabled
	if ($enabled = $site->enabled_plugins) {
		if (!is_array($enabled)) {
			$enabled = array($enabled);
		}
	} else {
		$enabled = array();
	}

	$enabled[] = $plugin;
	$enabled = array_unique($enabled);

	if ($return = $site->setMetaData('enabled_plugins', $enabled)) {

		// for other plugins that want to hook into this.
		$params = array('plugin' => $plugin, 'manifest' => $plugin_info);
		if ($return && !elgg_trigger_event('enable', 'plugin', $params)) {
			$return = FALSE;
		}

		// for this plugin's on_enable
		if ($return && isset($plugin_info['on_enable'])) {
			// pull in the actual plugin's start so the on_enable function is callable
			// NB: this will not run re-run the init hooks!
			$start = "{$CONFIG->pluginspath}$plugin/start.php";
			if (!file_exists($start) || !include($start)) {
				$return = FALSE;
			}

			// need language files for the messages
			$translations = "{$CONFIG->pluginspath}$plugin/languages/";
			register_translations($translations);
			if (!is_callable($plugin_info['on_enable'])) {
				$return = FALSE;
			} else {
				$on_enable = call_user_func($plugin_info['on_enable']);
				// allow null to mean "I don't care" like other subsystems
				$return = ($on_disable === FALSE) ? FALSE : TRUE;
			}
		}

		// disable the plugin if the on_enable or trigger results failed
		if (!$return) {
			array_pop($enabled);
			$site->setMetaData('enabled_plugins', $enabled);
		}

		$ENABLED_PLUGINS_CACHE = $enabled;
	}

	return $return;
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
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 * @throws InvalidClassException
 */
function disable_plugin($plugin, $site_guid = 0) {
	global $CONFIG, $ENABLED_PLUGINS_CACHE;

	$plugin = sanitise_string($plugin);
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$site = get_entity($site_guid);
	if (!($site instanceof ElggSite)) {
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($site_guid, "ElggSite"));
		throw new InvalidClassException();
	}

	if (!$plugin_info = load_plugin_manifest($plugin)) {
		return FALSE;
	}

	// getMetadata() doesn't return an array if only one plugin is enabled
	if ($enabled = $site->enabled_plugins) {
		if (!is_array($enabled)) {
			$enabled = array($enabled);
		}
	} else {
		$enabled = array();
	}

	$old_enabled = $enabled;

	// remove the disabled plugin from the array
	if (FALSE !== $i = array_search($plugin, $enabled)) {
		unset($enabled[$i]);
	}

	// if we're unsetting all the plugins, this will return an empty array.
	// it will fail with FALSE, though.
	$return = (FALSE === $site->enabled_plugins = $enabled) ? FALSE : TRUE;

	if ($return) {
		// for other plugins that want to hook into this.
		$params = array('plugin' => $plugin, 'manifest' => $plugin_info);
		if ($return && !elgg_trigger_event('disable', 'plugin', $params)) {
			$return = FALSE;
		}

		// for this plugin's on_disable
		if ($return && isset($plugin_info['on_disable'])) {
			if (!is_callable($plugin_info['on_disable'])) {
				$return = FALSE;
			} else {
				$on_disable = call_user_func($plugin_info['on_disable']);
				// allow null to mean "I don't care" like other subsystems
				$return = ($on_disable === FALSE) ? FALSE : TRUE;
			}
		}

		// disable the plugin if the on_enable or trigger results failed
		if (!$return) {
			$site->enabled_plugins = $old_enabled;
			$ENABLED_PLUGINS_CACHE = $old_enabled;
		} else {
			$ENABLED_PLUGINS_CACHE = $enabled;
		}
	}

	return $return;
}

/**
 * Return whether a plugin is enabled or not.
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 * @throws InvalidClassException
 */
function is_plugin_enabled($plugin, $site_guid = 0) {
	global $CONFIG, $ENABLED_PLUGINS_CACHE;

	if (!file_exists($CONFIG->pluginspath . $plugin)) {
		return false;
	}

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	if (!$ENABLED_PLUGINS_CACHE) {
		$site = get_entity($site_guid);
		if (!($site instanceof ElggSite)) {
			$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($site_guid, "ElggSite"));
			throw new InvalidClassException($msg);
		}

		$enabled_plugins = $site->enabled_plugins;
		if ($enabled_plugins && !is_array($enabled_plugins)) {
			$enabled_plugins = array($enabled_plugins);
		}
		$ENABLED_PLUGINS_CACHE = $enabled_plugins;
	}

	if (is_array($ENABLED_PLUGINS_CACHE)) {
		foreach ($ENABLED_PLUGINS_CACHE as $e) {
			if ($e == $plugin) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Register object, plugin entities as ElggPlugin classes
 *
 *  @return void
 */
function plugin_run_once() {
	// Register a class
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
