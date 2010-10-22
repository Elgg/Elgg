<?php
/**
 * Entity viewer
 *
 * @package Elgg
 * @subpackage Core
 */

set_context('search');
$area2 = elgg_list_entities();
set_context('entities');

$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

page_draw("", $body);