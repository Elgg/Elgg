<?php
/**
 * Elgg invite page
 * 
 * @package ElggFile
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @link http://elgg.org/
 */

function invitefriends_pagesetup() {
		
	// Menu options
	global $CONFIG;
	if (get_context() == "friends" || 
		get_context() == "friendsof") {
			add_submenu_item(elgg_echo('friends:invite'),$CONFIG->wwwroot."mod/invitefriends/",'invite');
		}
	}

	global $CONFIG;
	register_action('invitefriends/invite', false, $CONFIG->pluginspath . 'invitefriends/actions/invite.php');
	register_elgg_event_handler('pagesetup','system','invitefriends_pagesetup',1000);
?>