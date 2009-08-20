<?php

		/**
		 * Elgg plugins library
		 * Contains functions for managing plugins
		 * 
		 * @package Elgg
		 * @subpackage Core

		 * @author Curverider Ltd

		 * @link http://elgg.org/
		 */

		
		/// Cache enabled plugins per page
		$ENABLED_PLUGINS_CACHE = NULL;

		/**
		 * PluginException
		 *  
		 * A plugin Exception, thrown when an Exception occurs relating to the plugin mechanism. Subclass for specific plugin Exceptions.
		 * 
		 * @package Elgg
		 * @subpackage Exceptions
		 */
		class PluginException extends Exception {}
		
		/**
		 * @class ElggPlugin Object representing a plugin's settings for a given site.
		 * This class is currently a stub, allowing a plugin to saving settings in an object's metadata for each site.
		 * @author Curverider Ltd
		 */
		class ElggPlugin extends ElggObject
		{
			protected function initialise_attributes()
			{
				parent::initialise_attributes();
				
				$this->attributes['subtype'] = "plugin";
			}
			
			public function __construct($guid = null) 
			{			
				parent::__construct($guid);
			}
			
			/**
			 * Override entity get and sets in order to save data to private data store.
			 */
			public function get($name)
			{
				// See if its in our base attribute
				if (isset($this->attributes[$name])) {
					return $this->attributes[$name];
				}
				
				// No, so see if its in the private data store.
				$meta = get_private_setting($this->guid, $name);
				if ($meta)
					return $meta;
				
				// Can't find it, so return null
				return null;
			}

			/**
			 * Override entity get and sets in order to save data to private data store.
			 */
			public function set($name, $value)
			{
				if (array_key_exists($name, $this->attributes))
				{
					// Check that we're not trying to change the guid! 
					if ((array_key_exists('guid', $this->attributes)) && ($name=='guid'))
						return false;
						
					$this->attributes[$name] = $value;
				}
				else 
					return set_private_setting($this->guid, $name, $value);
			
				return true;
			}
		}
		
		/**
		 * Returns a list of plugins to load, in the order that they should be loaded.
		 *
		 * @return array List of plugins
		 */
		function get_plugin_list() {

			global $CONFIG;
			
			if (!empty($CONFIG->pluginlistcache))
				return $CONFIG->pluginlistcache;
			
			if ($site = get_entity($CONFIG->site_guid)) {
				
				$pluginorder = $site->pluginorder;
				if (!empty($pluginorder)) {
					
					$plugins = unserialize($pluginorder);
					
					$CONFIG->pluginlistcache = $plugins;
					return $plugins;
					
				} else {
					
					$plugins = array();
						
					if ($handle = opendir($CONFIG->pluginspath)) {
						while ($mod = readdir($handle)) {
							if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . "/" . $mod)) {
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
		 * @return array The new list of plugins and their orders
		 */
		function regenerate_plugin_list($pluginorder = false) {
			
			global $CONFIG;
			
			$CONFIG->pluginlistcache = null;
			
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
				if (sizeof($pluginorder))
					foreach($pluginorder as $key => $plugin) {
						if (is_dir($CONFIG->pluginspath . "/" . $plugin)) { 
							if ($key > $max)
								$max = $key;
						} else {
							unset($pluginorder[$key]);
						}
					}
					
				// Add new plugins to the end
				if ($handle = opendir($CONFIG->pluginspath)) {
					while ($mod = readdir($handle)) {
						if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . "/" . $mod)) {
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
				if (sizeof($pluginorder))
					foreach($pluginorder as $plugin) {
						$plugins[$key] = $plugin;
						$key = $key + 10;
					}
				
				$plugins = serialize($plugins);
				
				$site->pluginorder = $plugins;
				
				// Regenerate caches
				elgg_view_regenerate_simplecache();
				elgg_filepath_cache_reset();
				
				return $plugins;
					
			}
			
			return false;
			
		}
		

		/**
		 * For now, loads plugins directly
		 *
		 * @todo Add proper plugin handler that launches plugins in an admin-defined order and activates them on admin request
		 * @package Elgg
		 * @subpackage Core
		 */
		function load_plugins() {
			
			global $CONFIG;
			
			if (!empty($CONFIG->pluginspath)) {
				
				// See if we have cached values for things
				$cached_view_paths = elgg_filepath_cache_load();
				if ($cached_view_paths) $CONFIG->views = unserialize($cached_view_paths);
				
				// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
				if (file_exists($CONFIG->pluginspath . "disabled"))
					return;
				
				$plugins = get_plugin_list();
				
				if (sizeof($plugins))
				{
					foreach($plugins as $mod) {
						if (is_plugin_enabled($mod)) {
							if (file_exists($CONFIG->pluginspath . $mod)) {
								if (!include($CONFIG->pluginspath . $mod . "/start.php"))
									throw new PluginException(sprintf(elgg_echo('PluginException:MisconfiguredPlugin'), $mod));
									
								if (!$cached_view_paths)
								{
									if (is_dir($CONFIG->pluginspath . $mod . "/views")) {
										if ($handle = opendir($CONFIG->pluginspath . $mod . "/views")) {
											while ($viewtype = readdir($handle)) {
												if (!in_array($viewtype,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . $mod . "/views/" . $viewtype)) {
													autoregister_views("",$CONFIG->pluginspath . $mod . "/views/" . $viewtype,$CONFIG->pluginspath . $mod . "/views/", $viewtype);
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
				if (!$cached_view_paths)
					elgg_filepath_cache_save(serialize($CONFIG->views));
			}
			
		}
		
		/**
		 * Get the name of the most recent plugin to be called in the call stack (or the plugin that owns the current page, if any).
		 * 
		 * i.e., if the last plugin was in /mod/foobar/, get_plugin_name would return foo_bar.
		 *
		 * @param boolean $mainfilename If set to true, this will instead determine the context from the main script filename called by the browser. Default = false. 
		 * @return string|false Plugin name, or false if no plugin name was called
		 */
		function get_plugin_name($mainfilename = false) {
			if (!$mainfilename) {
				if ($backtrace = debug_backtrace()) { 
					foreach($backtrace as $step) {
						$file = $step['file'];
						$file = str_replace("\\","/",$file);
						$file = str_replace("//","/",$file);
						if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\/start\.php$/",$file,$matches)) {
							return $matches[1];
						}
					}
				}
			} else {
				//if (substr_count($file,'handlers/pagehandler')) {
				if (preg_match("/pg\/([a-zA-Z0-9\-\_]*)\//",$_SERVER['REQUEST_URI'],$matches)) {
					return $matches[1];
				} else {
					$file = $_SERVER["SCRIPT_NAME"];
					$file = str_replace("\\","/",$file);
					$file = str_replace("//","/",$file);
					if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\//",$file,$matches)) {
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
		 * <plugin_manifest>
		 * 	<field key="author" value="Curverider Ltd" />
		 *  <field key="version" value="1.0" />
		 * 	<field key="description" value="My plugin description, keep it short" />
		 *  <field key="website" value="http://www.elgg.org/" />
		 *  <field key="copyright" value="(C) Curverider 2008-2009" />
		 *  <field key="licence" value="GNU Public License version 2" />
		 * </plugin_manifest>
		 *
		 * @param string $plugin Plugin name.
		 * @return array of values
		 */
		function load_plugin_manifest($plugin)
		{
			global $CONFIG;
			
			$xml = xml_2_object(file_get_contents($CONFIG->pluginspath . $plugin. "/manifest.xml"));
			
			if ($xml)
			{
				$elements = array();
				
				foreach ($xml->children as $element)
				{
					$key = $element->attributes['key'];
					$value = $element->attributes['value'];
					
					$elements[$key] = $value;
				}
				
				return $elements;
			}
			
			return false;
		}
		
		/**
		 * This function checks a plugin manifest 'elgg_version' value against the current install
		 * returning TRUE if the elgg_version is <= the current install's version.
		 * @param $manifest_elgg_version_string The build version (eg 2009010201). 
		 * @return bool
		 */
		function check_plugin_compatibility($manifest_elgg_version_string)
		{
			$version = get_version();
			
			if (strpos($manifest_elgg_version_string, '.')===false)
			{
				// Using version
				$req_version = (int)$manifest_elgg_version_string;
				
				return ($version >= $req_version);
			}
			
			return false;
		}
		
		/**
		 * Shorthand function for finding the plugin settings.
		 * 
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you
		 * 								are calling from.
		 */
		function find_plugin_settings($plugin_name = "")
		{
			$plugins = get_entities('object', 'plugin', 0, "", 9999);
			$plugin_name = sanitise_string($plugin_name);
			if (!$plugin_name)
				$plugin_name = get_plugin_name();
	
			if ($plugins)
			{
				foreach ($plugins as $plugin)			
					if (strcmp($plugin->title, $plugin_name)==0)
						return $plugin;
			}
			
			return false;
		}
		
		/**
		 * Find the plugin settings for a user.
		 *
		 * @param string $plugin_name Plugin name.
		 * @param int $user_guid The guid who's settings to retrieve.
		 * @return array of settings in an associative array minus prefix.
		 */
		function find_plugin_usersettings($plugin_name = "", $user_guid = 0)
		{
			$plugin_name = sanitise_string($plugin_name);
			$user_guid = (int)$user_guid;
			
			if (!$plugin_name)
				$plugin_name = get_plugin_name();
				
			if ($user_guid == 0) $user_guid = get_loggedin_userid();
			
			// Get metadata for user
			$all_metadata = get_all_private_settings($user_guid); //get_metadata_for_entity($user_guid);
			if ($all_metadata)
			{
				$prefix = "plugin:settings:$plugin_name:";
				$return = new stdClass;
				
				foreach ($all_metadata as $key => $meta)
				{
					$name = substr($key, strlen($prefix));
					$value = $meta;
					
					if (strpos($key, $prefix) === 0)
						$return->$name = $value;
				}

				return $return;			
			}
			
			return false;
		}
		
		/**
		 * Set a user specific setting for a plugin.
		 *
		 * @param string $name The name - note, can't be "title".
		 * @param mixed $value The value.
		 * @param int $user_guid Optional user.
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you are calling from.
		 */
		function set_plugin_usersetting($name, $value, $user_guid = 0, $plugin_name = "")
		{
			$plugin_name = sanitise_string($plugin_name);
			$user_guid = (int)$user_guid;
			$name = sanitise_string($name);
			
			if (!$plugin_name)
				$plugin_name = get_plugin_name();
							
			$user = get_entity($user_guid);
			if (!$user) $user = get_loggedin_user();
			
			if (($user) && ($user instanceof ElggUser))
			{
				$prefix = "plugin:settings:$plugin_name:$name";
				//$user->$prefix = $value;
				//$user->save();
				
				// Hook to validate setting
				$value = trigger_plugin_hook('plugin:usersetting', 'user', array(
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
		 * Get a user specific setting for a plugin.
		 *
		 * @param string $name The name.
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you are calling from.
		 */
		function get_plugin_usersetting($name, $user_guid = 0, $plugin_name = "")
		{
			$plugin_name = sanitise_string($plugin_name);
			$user_guid = (int)$user_guid;
			$name = sanitise_string($name);
			
			if (!$plugin_name)
				$plugin_name = get_plugin_name();
				
			$user = get_entity($user_guid);
			if (!$user) $user = get_loggedin_user();
			
			if (($user) && ($user instanceof ElggUser))
			{
				$prefix = "plugin:settings:$plugin_name:$name";
				return get_private_setting($user->guid, $prefix); //$user->$prefix;
			}
			
			return false;
		}
			
		/**
		 * Set a setting for a plugin.
		 *
		 * @param string $name The name - note, can't be "title".
		 * @param mixed $value The value.
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you are calling from.
		 */
		function set_plugin_setting($name, $value, $plugin_name = "")
		{	
			if (!$plugin_name) $plugin_name = get_plugin_name();	
			$plugin = find_plugin_settings($plugin_name);
			
			if (!$plugin) 
				$plugin = new ElggPlugin();
				
			if ($name!='title') 
			{
				// Hook to validate setting
				$value = trigger_plugin_hook('plugin:setting', 'plugin', array(
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
		 * @param string $name The name.
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you are calling from.
		 */
		function get_plugin_setting($name, $plugin_name = "")
		{
			$plugin = find_plugin_settings($plugin_name);
		
			if ($plugin)
				return $plugin->$name;
			
			return false;
		}
		
		/**
		 * Clear a plugin setting.
		 *
		 * @param string $name The name.
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you are calling from.
		 */
		function clear_plugin_setting($name, $plugin_name = "")
		{
			$plugin = find_plugin_settings($plugin_name);
			
			if ($plugin)
				return remove_all_private_settings($plugin->guid); //$plugin->clearMetaData($name);
			
			return false;
		}
		
		/**
		 * Return an array of installed plugins.
		 */
		function get_installed_plugins()
		{
			global $CONFIG;
			
			$installed_plugins = array();
			
			if (!empty($CONFIG->pluginspath)) {
				
				$plugins = get_plugin_list();
				
				foreach($plugins as $mod) {
					$installed_plugins[$mod] = array();
					$installed_plugins[$mod]['active'] = is_plugin_enabled($mod);
					$installed_plugins[$mod]['manifest'] = load_plugin_manifest($mod);
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
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 */
		function enable_plugin($plugin, $site_guid = 0)
		{
			global $CONFIG, $ENABLED_PLUGINS_CACHE;
			
			$plugin = sanitise_string($plugin);
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = $CONFIG->site_guid;
				
			$site = get_entity($site_guid);
			if (!($site instanceof ElggSite))
				throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $site_guid, "ElggSite"));
				
			$enabled = $site->getMetaData('enabled_plugins');
			$new_enabled = array();
			if ($enabled)
			{
				if (!is_array($enabled))
					$new_enabled[] = $enabled;
				else
					$new_enabled = $enabled;
			}
			$new_enabled[] = $plugin;
			$new_enabled = array_unique($new_enabled);
			
			$return = $site->setMetaData('enabled_plugins', $new_enabled);
			$ENABLED_PLUGINS_CACHE = $new_enabled;
			
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
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 */
		function disable_plugin($plugin, $site_guid = 0)
		{
			global $CONFIG, $ENABLED_PLUGINS_CACHE;
			
			$plugin = sanitise_string($plugin);
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = $CONFIG->site_guid;
				
			$site = get_entity($site_guid);
			if (!($site instanceof ElggSite))
				throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $site_guid, "ElggSite"));
				
			$enabled = $site->getMetaData('enabled_plugins');
			$new_enabled = array();
		
			foreach ($enabled as $plug)
				if ($plugin != $plug)
					$new_enabled[] = $plug;
					
			$return = $site->setMetaData('enabled_plugins', $new_enabled);
			$ENABLED_PLUGINS_CACHE = $new_enabled;
					
			return $return;
		}
		
		/**
		 * Return whether a plugin is enabled or not.
		 *
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 * @return bool
		 */
		function is_plugin_enabled($plugin, $site_guid = 0)
		{
			global $CONFIG, $ENABLED_PLUGINS_CACHE;
			
			if (!file_exists($CONFIG->pluginspath . $plugin)) return false;
			
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = $CONFIG->site_guid;
				
				
			if (!$ENABLED_PLUGINS_CACHE) {
				$site = get_entity($site_guid);
				if (!($site instanceof ElggSite))
					throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $site_guid, "ElggSite"));
			
				$ENABLED_PLUGINS_CACHE = $site->enabled_plugins;
			}
				
			foreach ($ENABLED_PLUGINS_CACHE as $e)
				if ($e == $plugin) return true;
				
			return false;
		}
		
		/**
		 * Run once and only once.
		 */
		function plugin_run_once()
		{
			// Register a class
			add_subtype("object", "plugin", "ElggPlugin");	
		}
		
		/** 
		 * Initialise the file modules. 
		 * Listens to system boot and registers any appropriate file types and classes 
		 */
		function plugin_init()
		{
			// Now run this stuff, but only once
			run_function_once("plugin_run_once");
			
			// Register some actions
			register_action("plugins/settings/save", false, "", true);
			register_action("plugins/usersettings/save");
			
			register_action('admin/plugins/enable', false, "", true); // Enable
			register_action('admin/plugins/disable', false, "", true); // Disable
			register_action('admin/plugins/enableall', false, "", true); // Enable all
			register_action('admin/plugins/disableall', false, "", true); // Disable all
			
			register_action('admin/plugins/reorder', false, "", true); // Reorder
			
		}
		
		// Register a startup event
		register_elgg_event_handler('init','system','plugin_init');	
?>