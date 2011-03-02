<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

echo elgg_view('admin/statistics/extend');

echo elgg_view_module('inline', elgg_echo('admin:statistics:label:basic'), elgg_view('admin/statistics/basic'));

echo elgg_view_module('inline', elgg_echo('admin:statistics:label:numentities'), elgg_view('admin/statistics/numentities'));