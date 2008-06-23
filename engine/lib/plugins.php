<?php

		/**
		 * Elgg plugins library
		 * Contains functions for managing plugins
		 * 
		 * @package Elgg
		 * @subpackage Core
		 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
		 * @author Curverider Ltd
		 * @copyright Curverider Ltd 2008
		 * @link http://elgg.org/
		 */


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
		 * @author Marcus Povey
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
				
				if ($handle = opendir($CONFIG->pluginspath)) {
					while ($mod = readdir($handle)) {
						if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . "/" . $mod)) {
							if (is_plugin_enabled($mod))
							{
								if (!@include($CONFIG->pluginspath . $mod . "/start.php"))
									throw new PluginException(sprintf(elgg_echo('PluginException:MisconfiguredPlugin'), $mod));
								if (is_dir($CONFIG->pluginspath . $mod . "/views/default")) {
									autoregister_views("",$CONFIG->pluginspath . $mod . "/views/default",$CONFIG->pluginspath . $mod . "/views/");
								}
								if (is_dir($CONFIG->pluginspath . $mod . "/languages")) {
									register_translations($CONFIG->pluginspath . $mod . "/languages/");
								}
							}
						}
					}
				}
				
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
				$file = $_SERVER["SCRIPT_NAME"];
				$file = str_replace("\\","/",$file);
				$file = str_replace("//","/",$file);
				if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\//",$file,$matches)) {
					return $matches[1];
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
		 * 	<field key="author" value="Marcus Povey" />
		 *  <field key="version" value="1.0" />
		 * 	<field key="description" value="My plugin description, keep it short" />
		 *  <field key="website" value="http://www.elgg.org/" />
		 *  <field key="copyright" value="(C) Curverider 2008" />
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
		 * Shorthand function for finding the plugin settings.
		 * 
		 * @param string $plugin_name Optional plugin name, if not specified then it is detected from where you
		 * 								are calling from.
		 */
		function find_plugin_settings($plugin_name = "")
		{
			$plugins = get_entities('object', 'plugin');
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
		 */
		function find_plugin_usersettings($plugin_name = "", $user_guid = 0)
		{
			$plugin_name = sanitise_string($plugin_name);
			$user_guid = (int)$user_guid;
			
			if (!$plugin_name)
				$plugin_name = get_plugin_name();
				
			if ($user_guid == 0) $user_guid = $_SESSION['user']->guid;
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
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
			$plugin = find_plugin_settings($plugin_name);
			
			if (!$plugin) 
				$plugin = new ElggPlugin();
				
			if ($name!='title') 
			{
				$plugin->title = $plugin_name;
				$plugin->$name = $value;
				$plugin->save();
				
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
				return $plugin->clearMetaData($name);
			
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
				
				if ($handle = opendir($CONFIG->pluginspath)) {
					
					while ($mod = readdir($handle)) {
						
						if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . "/" . $mod)) {
							
							$installed_plugins[$mod] = array();
							$installed_plugins[$mod]['active'] = is_plugin_enabled($mod);
							$installed_plugins[$mod]['manifest'] = load_plugin_manifest($mod);
						}
					}
				}
			}
			
			return $installed_plugins;
		}
		
		/**
		 * Enable a plugin for a site (default current site)
		 *
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 */
		function enable_plugin($plugin, $site_guid = 0)
		{
			global $CONFIG;
			
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
			
			return $site->setMetaData('enabled_plugins', $new_enabled);
		}
		
		/**
		 * Disable a plugin for a site (default current site)
		 *
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 */
		function disable_plugin($plugin, $site_guid = 0)
		{
			global $CONFIG;
			
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
					
			return $site->setMetaData('enabled_plugins', $new_enabled);
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
			//return execute_privileged_codeblock('__is_plugin_enabled_priv', array('plugin'=>$plugin, 'site_guid' => $site_guid));
				// Does this need to be in privileged? Doesn't seem to...
			
			global $CONFIG;
			
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = $CONFIG->site_guid;
				
			$site = get_entity($site_guid);
			if (!($site instanceof ElggSite))
				throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $site_guid, "ElggSite"));
				
			$enabled = find_metadata("enabled_plugins", $plugin, "site", "", 10, 0, "", $site_guid);
			if ($enabled)
				return true;
				
			return false;
		}
		
		/**
		 * Privileged execution so code can run before user logged in.
		 *
		 * @param array $params
		 * @return bool
		 */
		/*function __is_plugin_enabled_priv(array $params = null)
		{
			global $CONFIG;
			
			$plugin = $params['plugin'];
			$site_guid = (int) $params['site_guid'];
			if ($site_guid == 0)
				$site_guid = $CONFIG->site_guid;
				
			$site = get_entity($site_guid);
			if (!($site instanceof ElggSite))
				throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $site_guid, "ElggSite"));
				
			$enabled = find_metadata("enabled_plugins", $plugin, "site", "", 10, 0, "", $site_guid);
			if ($enabled)
				return true;
				
			return false;
		}*/
		
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
			
			register_action('admin/plugins/enable', false, "", true); // Enable
			register_action('admin/plugins/disable', false, "", true); // Disable
		}
		
		// Register a startup event
		register_elgg_event_handler('init','system','plugin_init');	
?>