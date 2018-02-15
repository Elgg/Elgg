<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

echo elgg_view_module('info', elgg_echo('admin:statistics:label:user'), elgg_view('admin/statistics/user'));

echo elgg_view_module('info', elgg_echo('admin:statistics:label:numentities'), elgg_view('admin/statistics/numentities'));
