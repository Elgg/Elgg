<?php
/**
 * Elgg widget reorder action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$owner = get_input('owner');
$context = get_input('context');

$maincontent = get_input('debugField1');
$sidebar = get_input('debugField2');
$rightbar = get_input('debugField3');

$result = reorder_widgets_from_panel($maincontent, $sidebar, $rightbar, $context, $owner);

if (!$result) {
	register_error(elgg_echo('widgets:move:failure'));
}

forward(REFERER);