<?php

/**
 * Elgg thewire: add shout action
 *
 * @package Elggthewire
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

// Get input data
$body = get_input('note');
$access_id = (int)get_default_access();
if ($access_id == ACCESS_PRIVATE) {
	$access_id = ACCESS_LOGGED_IN; // Private wire messages are pointless
}
$method = get_input('method');
$parent = (int)get_input('parent', 0);
if (!$parent) {
	$parent = 0;
}
// Make sure the body isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
	forward("mod/thewire/add.php");
}

if (!thewire_save_post($body, $access_id, $parent, $method)) {
	register_error(elgg_echo("thewire:error"));
	forward("mod/thewire/add.php");
}


// Success message
system_message(elgg_echo("thewire:posted"));

// Forward
forward("mod/thewire/everyone.php");

?>