<?php
/**
 * Elgg user settings system index
 *
 * @package Elgg
 * @subpackage Core
 */

// Get the Elgg framework
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

if (!page_owner()) {
	set_page_owner(get_loggedin_userid());
}

// Make sure we don't open a security hole ...
if ((!page_owner_entity()) || (!page_owner_entity()->canEdit())) {
	set_page_owner(get_loggedin_userid());
}

// Forward to the user settings
forward('pg/settings/user/' . page_owner_entity()->username . "/");