<?php
/**
 * Assembles and outputs the forgotten password page.
 *
 * @see views/default/account/forums/forgotten_password.php
 *
 * @package Elgg
 * @subpackage Core
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

if (!isloggedin()) {
	$area1 = elgg_view_title(elgg_echo("user:password:lost"));
	$area2 = elgg_view("account/forms/forgotten_password");
	page_draw(elgg_echo('user:password:lost'), elgg_view_layout("one_column_with_sidebar", $area1 . $area2));
} else {
	forward();
}