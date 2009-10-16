<?php
/**
 * Elgg user settings functions.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get the Elgg framework
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

// Make sure only valid users can see this
gatekeeper();

// Make sure we don't open a security hole ...
if ((!page_owner_entity()) || (!page_owner_entity()->canEdit())) {
	set_page_owner($_SESSION['guid']);
}

// Display main admin menu
page_draw(elgg_echo("usersettings:plugins"),elgg_view_layout('two_column_left_sidebar','',elgg_view_title(elgg_echo("usersettings:plugins")) . elgg_view("usersettings/plugins", array('installed_plugins' => get_installed_plugins()))));
