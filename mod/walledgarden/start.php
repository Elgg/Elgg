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
		
		register_plugin_hook('new_twitter_user', 'twitter_service', 'walledgarden_new_twitter_user');
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

	function walledgarden_new_twitter_user($hook, $entity_type, $returnvalue, $params) {
		// do not allow new users to be created within the walled-garden
		register_error(elgg_echo('walledgarden:new_user:fail'));
		return FALSE;
	}
	
	register_elgg_event_handler('init','system','walledgarden_init');
	register_elgg_event_handler('pagesetup','system','walledgarden_pagesetup');
?>
