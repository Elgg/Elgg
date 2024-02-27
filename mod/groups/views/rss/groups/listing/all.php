<?php
/**
 * Renders a list of groups (default sorted by creation date)
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['getter']  Function to get entities (default: 'elgg_get_entities')
 */

$defaults = [
	'type' => 'group',
	'full_view' => false,
	'pagination' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

$getter = (string) elgg_extract('getter', $vars, 'elgg_get_entities');

echo elgg_list_entities($options, $getter);
