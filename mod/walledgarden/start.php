<?php

	/**
	 * Walled garden support.
	 * 
	 * @package ElggWalledGarden
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	function walledgarden_init()
	{
		global $CONFIG;
		
		$CONFIG->disable_registration = true;
		
		// elgg_set_viewtype('default');
		elgg_extend_view('pageshells/pageshell', 'walledgarden/walledgarden');
		
		elgg_extend_view('css','walledgarden/css');
		
		// Replace the default index page if the custom index plugin does not exist
		if(!is_plugin_enabled("custom_index"))
			register_plugin_hook('index','system','walledgarden_index');
		// um...what is this doing here?
		// Turn off simple cache if enabled
		//if (function_exists('elgg_view_disable_simplecache')) {
		//	elgg_view_disable_simplecache();
		//}

	}
	
	function walledgarden_pagesetup() {
		
		global $CONFIG;
		if (current_page_url() != $CONFIG->url
			&& !defined('externalpage')
			&& !isloggedin()) {
				forward();
				exit;
			}
		
	}
	
	 function walledgarden_index() {
			
			if (!include_once(dirname(dirname(__FILE__))) . "/walledgarden/index.php") {
				return false;
			}
			return true;
			
		}
	
	register_elgg_event_handler('init','system','walledgarden_init');
	register_elgg_event_handler('pagesetup','system','walledgarden_pagesetup');
?>
