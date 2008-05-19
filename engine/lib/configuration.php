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
	 * Sets a configuration value
	 *
	 * @param string $name The name of the configuration value
	 * @param string $value Its value
	 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
	 * @return false|int 1 or false depending on success or failure 
	 */
		function set_config($name, $value, $site_guid = 0) {
			
			global $CONFIG;
			$name = mysql_real_escape_string($name);
			$value = mysql_real_escape_string($value);
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = (int) $CONFIG->site_id;
			$CONFIG->$name = $value;
			$value = serialize($value);
			return insert_data("insert into {$CONFIG->dbprefix}config set name = '{$name}', value = '{$value}', site_guid = {$site_guid}");
			
		}

	/**
	 * Gets a configuration value
	 *
	 * @param string $name The name of the config value
	 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
	 * @return mixed|false Depending on success
	 */
		function get_config($name, $site_guid = 0) {
			
			global $CONFIG;
			if (isset($CONFIG->$name))
				return $CONFIG->$name;
			$name = mysql_real_escape_string($name);
			$site_guid = (int) $site_guid;
			if ($site_guid == 0)
				$site_guid = (int) $CONFIG->site_id;
			if ($result = get_data_row("select value from {$CONFIG->dbprefix}config where name = '{$name}' and site_guid = {$site_guid}")) {
				$result = $result->value;
				$result = unserialize($result->value);
				$CONFIG->$name = $result;
				return $result;
			}
			return false;
			
		}

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
				/*
				$CONFIG->wwwroot = "http://" . $_SERVER['SERVER_NAME'];
				
				$request = $_SERVER['REQUEST_URI'];
				
				if (strripos($request,"/") < (strlen($request) - 1)) {
					// addressing a file directly, not a dir
					$request = substr($request, 0, strripos($request,"/")+1);
				}
				
				$CONFIG->wwwroot .= $request;
				*/
				$CONFIG->wwwroot = "http://" . $_SERVER['HTTP_HOST'] . str_replace("//","/",str_replace($_SERVER['DOCUMENT_ROOT'],"",$CONFIG->path));
		
			}
		
			if (empty($CONFIG->url))
				$CONFIG->url = $CONFIG->wwwroot;
			
			if (empty($CONFIG->sitename))
				$CONFIG->sitename = "New Elgg site";
				
			if (empty($CONFIG->debug))
				$CONFIG->debug = false;

		}
		
	/**
	 * Function that provides some config initialisation on system init
	 *
	 */
		
		function configuration_init() {
			
			global $CONFIG;
			
			$path = datalist_get('path');
			if (!empty($path))
				$CONFIG->path = $path;
			$dataroot = datalist_get('dataroot');
			if (!empty($dataroot))
				$CONFIG->dataroot = $dataroot;
			if (isset($CONFIG->site) && (get_class($CONFIG->site) == "ElggSite")) {
				$CONFIG->wwwroot = $CONFIG->site->url;
				$CONFIG->sitename = $CONFIG->site->name;
			}
			$CONFIG->url = $CONFIG->wwwroot;
			
			return true;
			
		}
		
	/**
	 * Register config_init
	 */

		register_event_handler('boot','system','configuration_init',10);
		
?>