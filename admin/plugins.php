<?php
/**
 * Elgg administration plugin system index
 * This is a special page that permits the configuration of plugins in a standard way.
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

// Regenerate plugin list
regenerate_plugin_list();

// Display main admin menu
$vars = array('installed_plugins' => get_installed_plugins());

$title = elgg_view_title(elgg_echo('admin:plugins'));
$main_box = elgg_view("admin/plugins", $vars);
$content = elgg_view_layout("two_column_left_sidebar", '', $title . $main_box);

page_draw(elgg_echo('admin:plugins'), $content);
