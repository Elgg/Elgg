<?php
/**
 * Assembles and outputs the registration page.
 *
 * Since 1.8 registration can be disabled via administration.  If this is
 * the case, calls to this page will forward to the network front page.
 *
 * If the user is logged in, this page will forward to the network
 * front page.
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

/**
 * Start the Elgg engine
 *
 * Why?
 * Tthere _might_ exist direct calls to this file, requiring the engine
 * to be started. Logic for both cases follow.
 *
 * @todo remove as direct calls were deprecated in 1.7
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// check new registration allowed
if (elgg_get_config('allow_registration') == false) {
	register_error(elgg_echo('registerdisabled'));
	forward();
}

$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

// only logged out people need to register
if (isloggedin()) {
	forward();
}

$area1 = elgg_view_title(elgg_echo("register"));
$area2 = elgg_view("account/forms/register", array(
	'friend_guid' => $friend_guid,
	'invitecode' => $invitecode)
);

$body = elgg_view_layout("one_column_with_sidebar", array('content' => $area1 . $area2));
echo elgg_view_page(elgg_echo("register"), $body);
