<?php
/**
 * Elgg save widget settings action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$guid = get_input('guid');
$params = get_input('params');

$widget = get_entity($guid);
if ($widget && $widget->saveSettings($params)) {
	$view = "widgets/$widget->handler/view";
	echo elgg_view($view, array('entity' => $widget));
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

forward(REFERER);