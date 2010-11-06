<?php

/**
 * Elgg members plugin
 * This plugin has some interesting options for users; see who is online, site members, 
 * 
 * @package Elggmembers
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

register_elgg_event_handler('init', 'system', 'members_init');

function members_init() {
	global $CONFIG;
	
	elgg_extend_view('css', 'members/css');

	register_page_handler('members', 'members_page_handler');
	
	// Set up menu for logged in users
	if (isloggedin()) {
		add_menu(elgg_echo('members:members'), $CONFIG->wwwroot . "pg/members/all/");
	}
	
}

/**
 * Members page handler
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function members_page_handler($page) {

	switch ($page[0]) {
		case "all":
		default:
			require(dirname(__FILE__) . "/index.php");
			break;
	}

	return true;
}
