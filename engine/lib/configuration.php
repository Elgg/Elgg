<?php

	/**
	 * Elgg configuration library
	 * Contains functions for managing system configuration
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * If certain configuration elements don't exist, autodetect sensible defaults 
	 * 
	 * @uses $CONFIG The main configuration global
	 *
	 */
		function set_default_config() {
			
			global $CONFIG;
			
			if (empty($CONFIG->path))
				$CONFIG->path = str_replace("\\","/",dirname(dirname(dirname(__FILE__)))) . "/";

			if (empty($CONFIG->viewpath))
				$CONFIG->viewpath = $CONFIG->path . "views/";	

			if (empty($CONFIG->pluginspath))
				$CONFIG->pluginspath = $CONFIG->path . "mod/";
				
			if (empty($CONFIG->wwwroot)) {
				$CONFIG->wwwroot = "http://" . $_SERVER['SERVER_NAME'];
				if (strripos($_SERVER['DOCUMENT_ROOT'],"/") < (sizeof($_SERVER['DOCUMENT_ROOT']) - 1)) {
					$CONFIG->wwwroot .= "/";
				}
				$CONFIG->wwwroot .= str_replace($_SERVER['DOCUMENT_ROOT'],"",$CONFIG->path);
			}
			
			if (empty($CONFIG->url))
				$CONFIG->url = $CONFIG->wwwroot;
			
			if (empty($CONFIG->sitename))
				$CONFIG->sitename = "New Elgg site";
			
		}

?>