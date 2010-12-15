<?php
/**
 * Assembles and outputs the forgotten password page.
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

if (isloggedin()) {
	forward();
}

$title = elgg_echo("user:password:lost");
$content = elgg_view_title($title);

$content .= elgg_view_form('user/requestnewpassword');

$body = elgg_view_layout("one_column_with_sidebar", array('content' => $content));

echo elgg_view_page($title, $body);
