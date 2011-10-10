<?php
/**
 * Elgg save widget settings action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

elgg_set_context('widgets');

$guid = get_input('guid');
$params = get_input('params');
$default_widgets = get_input('default_widgets', 0);

$widget = get_entity($guid);
if ($widget && $widget->saveSettings($params)) {
	elgg_set_page_owner_guid($widget->getContainerGUID());
	if (!$default_widgets) {
		if (elgg_view_exists("widgets/$widget->handler/content")) {
			$view = "widgets/$widget->handler/content";
		} else {
			elgg_deprecated_notice("widgets use content as the display view", 1.8);
			$view = "widgets/$widget->handler/view";
		}
		echo elgg_view($view, array('entity' => $widget));
	}
} else {
	register_error(elgg_echo('widgets:save:failure'));
}

forward(REFERER);