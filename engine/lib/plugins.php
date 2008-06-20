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
							//if (is_plugin_enabled($mod))
							//{
								if (!@include($CONFIG->pluginspath . $mod . "/start.php"))
									throw new PluginException(sprintf(elgg_echo('PluginException:MisconfiguredPlugin'), $mod));
								if (is_dir($CONFIG->pluginspath . $mod . "/views/default")) {
									autoregister_views("",$CONFIG->pluginspath . $mod . "/views/default",$CONFIG->pluginspath . $mod . "/views/");
								}
								if (is_dir($CONFIG->pluginspath . $mod . "/languages")) {
									register_translations($CONFIG->pluginspath . $mod . "/languages/");
								}
							//}
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
		 * Register a plugin with a manifest.
		 *
		 * It is passed an associated array of values. Currently the following fields are recognised:
		 * 
		 * 'author', 'description', 'version', 'website' & 'copyright'.
		 * 
		 * @param array $manifest An associative array representing the manifest.
		 */
		function register_plugin_manifest(array $manifest)
		{
			global $CONFIG;
			
			if (!is_array($CONFIG->plugin_manifests))
				$CONFIG->plugin_manifests = array();
				
			$plugin_name = get_plugin_name();
			
			if ($plugin_name)
			{
				$CONFIG->plugin_manifests[$plugin_name] = $manifest;
			}
			else
				throw new PluginException(elgg_echo('PluginException:NoPluginName'));
		}
		
		/**
		 * Register a basic plugin manifest.
		 *
		 * @param string $author The author.
		 * @param string $description A description of the plugin (don't forget to internationalise this string!)
		 * @param string $version The version
		 * @param string $website A link to the plugin's website
		 * @param string $copyright Copyright information
		 * @return bool
		 */
		function register_plugin_manifest_basic($author, $description, $version, $website = "", $copyright = "")
		{
			return register_plugin_manifest(array(
				'version' => $version,
				'author' => $author,
				'description' => $description,
				'website' => $website,
				'copyright' => $copyright
			));
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
				foreach ($plugins as $plugins)
					if (strcmp($plugin->title, $plugin_name)==0)
						return $plugin;
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
			$plugin = find_plugin_settings($plugin_name);
			
			if (!$plugin)
				$plugin = new ElggPlugin();
				
			if ($name!='title') 
			{
				$plugin->$name = $value;
				
				$plugin->save();
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
		 * Enable a plugin for a site (default current site)
		 *
		 * @param string $plugin The plugin name.
		 * @param int $site_guid The site id, if not specified then this is detected.
		 */
		function enable_plugin($plugin, $site_guid = 0)
		{
			global $CONFIG;
			
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
		}
		
		// Register a startup event
		register_elgg_event_handler('init','system','plugin_init');	
?>