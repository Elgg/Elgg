<?php
/**
 * Elgg administration user system index
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

// Are we performing a search
$search = get_input('s');
$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$context = get_context();

$title = elgg_view_title(elgg_echo('admin:user'));

set_context('search');

$result = elgg_list_entities(array('type' => 'user', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));

set_context('admin');

// Display main admin menu
page_draw(elgg_echo("admin:user"),
	elgg_view_layout("two_column_left_sidebar",
		'',
		$title . elgg_view("admin/user") . $result
	)
);
