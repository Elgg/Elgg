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
	 */
		function load_plugins() {

			global $CONFIG;
			if (!empty($CONFIG->pluginspath)) {
				
				if ($handle = opendir($CONFIG->pluginspath)) {
					while ($mod = readdir($handle)) {
						if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->pluginspath . "/" . $mod)) {
							if (!@include($CONFIG->pluginspath . $mod . "/start.php"))
								throw new PluginException("{$mod} is a misconfigured plugin.");
						}
					}
				}
				
			}
			
		}
		
	/**
	 * @class PluginException 
	 * A plugin Exception, thrown when an Exception occurs relating to the plugin mechanism. Subclass for specific plugin Exceptions.
	 */
		
		class PluginException extends Exception {}

?>