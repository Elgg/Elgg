<?php
/**
 * Elgg save widget settings action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 *
 * @uses int    $_REQUEST['guid']            The guid of the widget to save
 * @uses array  $_REQUEST['params']          An array of params to set on the widget.
 * @uses int    $_REQUEST['default_widgets'] Flag for if these settings are for default wigets.
 * @uses string $_REQUEST['context']         An optional context of the widget. Used to return
 *                                           the correct output if widget content changes
 *                                           depending on context.
 *
 */

elgg_set_context('widgets');

$guid = get_input('guid');
$params = get_input('params');
$default_widgets = get_input('default_widgets', 0);
$context = get_input('context');

$widget = get_entity($guid);
if ($widget && $widget->saveSettings($params)) {
	elgg_set_page_owner_guid($widget->getContainerGUID());
	if ($context) {
		elgg_push_context($context);
	}
	
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