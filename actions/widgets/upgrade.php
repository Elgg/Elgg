<?php
/**
 * Upgrade default widgets for Elgg 1.8
 *
 * Pre-1.8, default widgets were stored as metadata on a defaultwidgets object.
 * Now they are stored as widget objects owned by the site.
 * 
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$object = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'moddefaultwidgets',
	'limit' => 1,
));

if (!$object) {
	forward(REFERER);
}

$object = $object[0];

$site = elgg_get_site_entity();

$ia = elgg_set_ignore_access(true);
foreach (array('profile', 'dashboard') as $context) {
	if (isset($object->$context)) {
		elgg_push_context($context);
		elgg_push_context('default_widgets');
		elgg_push_context('widgets');

		// deserialize the widget information
		list($left, $middle, $right) = split('%%', $object->$context);
		$left_widgets = split('::', $left);
		$middle_widgets = split('::', $middle);
		$right_widgets = split('::', $right);

		// 1st column is right column in default theme
		$widgets = array(
			1 => array_reverse($right_widgets),
			2 => array_reverse($middle_widgets),
			3 => array_reverse($left_widgets),
		);

		foreach ($widgets as $column => $column_widgets) {
			foreach ($column_widgets as $handler) {
				$guid = elgg_create_widget($site->getGUID(), $handler, $context);
				if ($guid) {
					$widget = get_entity($guid);
					$widget->move($column, 0);
				}
			}
		}

		elgg_pop_context();
		elgg_pop_context();
		elgg_pop_context();
	}
}
elgg_set_ignore_access($ia);

$object->delete();
system_message(elgg_echo('upgrade:core'));
forward(REFERER);
