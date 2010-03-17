<?php
/**
 * Elgg administration menu items
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
admin_gatekeeper();

$vars = array(
	'menu_items' => get_register('menu')
);

$main_box = elgg_view("admin/menu_items", $vars);
$content = elgg_view_layout("one_column_with_sidebar", $main_box);

page_draw(elgg_echo('admin:plugins'), $content);