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
	 * PluginException
	 *  
	 * A plugin Exception, thrown when an Exception occurs relating to the plugin mechanism. Subclass for specific plugin Exceptions.
	 * 
	 * @package Elgg
	 * @subpackage Exceptions
	 */
		
		class PluginException extends Exception {}

?>