<?php
/**
 * Assembles and outputs the forgotten password page.
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

if (!isloggedin()) {
	$area1 = elgg_view_title(elgg_echo("user:password:lost"));
	$area2 = elgg_view("account/forms/forgotten_password");
	$content = elgg_view_layout("one_column_with_sidebar", $area1 . $area2);
	page_draw(elgg_echo('user:password:lost'), $content);
} else {
	forward();
}