<?php
/**
 * Entity viewer
 *
 * @package Elgg
 * @subpackage Core
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

set_context('search');
$area2 = elgg_list_entities();

set_context('entities');
$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

page_draw("", $body);