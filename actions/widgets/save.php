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
	elgg_set_page_owner_guid($widget->getContainerGUID());
	$view = "widgets/$widget->handler/content";
	echo elgg_view($view, array('entity' => $widget));
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

forward(REFERER);