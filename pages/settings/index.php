<?php
/**
 * Elgg user settings system index
 *
 * @package Elgg
 * @subpackage Core
 */

if (!elgg_get_page_owner_guid()) {
	set_page_owner(get_loggedin_userid());
}

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner()) || (!elgg_get_page_owner()->canEdit())) {
	set_page_owner(get_loggedin_userid());
}

// Forward to the user settings
forward('pg/settings/user/' . elgg_get_page_owner()->username . "/");