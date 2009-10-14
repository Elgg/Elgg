<?php
/**
 * Forgotten password function.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

if (!isloggedin()) {
	$body = elgg_view_title(elgg_echo('user:password:lost')) . elgg_view("account/forms/forgotten_password");

	page_draw(elgg_echo('user:password:lost'), elgg_view_layout("one_column", $body));
} else {
	forward();
}