<?php
/**
 * Entity viewer
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_push_context('search');
$area2 = elgg_list_entities();
elgg_pop_context();

$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

echo elgg_view_page("", $body);