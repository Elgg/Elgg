<?php
/**
 * Elgg administration statistics index
 * This is a special page that displays a number of statistics
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get the Elgg framework
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

// Make sure only valid admin users can see this
admin_gatekeeper();

// Display main admin menu
$title = elgg_view_title(elgg_echo('admin:statistics'));
$main_box = elgg_view("admin/statistics");
$content = elgg_view_layout("two_column_left_sidebar", '', $title . $main_box);

page_draw(elgg_echo("admin:statistics"), $content);