<?php
/**
 * Elgg widget add action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$owner_guid = get_input('owner_guid');
$handler = get_input('handler');
$context = get_input('context');
$column = get_input('column', 1);
$default_widgets = get_input('default_widgets', 0);

elgg_push_context($context);
if ($default_widgets) {
	elgg_push_context('default_widgets');
}
elgg_push_context('widgets');

if (!empty($owner_guid)) {
	$owner = get_entity($owner_guid);
	if ($owner && $owner->canEdit()) {
		$guid = elgg_create_widget($owner->getGUID(), $handler, $context);
		if ($guid) {
			$widget = get_entity($guid);

			// position the widget
			$widget->move($column, 0);

			// send widget html for insertion
			echo elgg_view_entity($widget);

			//system_message(elgg_echo('widgets:add:success'));
			forward(REFERER);
		}
	}
}

register_error(elgg_echo('widgets:add:failure'));
forward(REFERER);
