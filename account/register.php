<?php

/**
 * Elgg registration page
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$friend_guid = (int) get_input('friend_guid',0);
$invitecode = get_input('invitecode');

// If we're not logged in, display the registration page
if (!isloggedin()) {
	page_draw(elgg_echo('register'), elgg_view("account/forms/register", array('friend_guid' => $friend_guid, 'invitecode' => $invitecode)));
// Otherwise, forward to the index page
} else {
	forward();
}
