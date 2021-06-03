<?php
/**
 * List river activity
 *
 * @uses $vars['options']     Additional listing options
 * @uses $vars['show_filter'] Add river filter selector (default: false)
 */

// filter
$show_filter = (bool) elgg_extract('show_filter', $vars, false);
unset($vars['show_filter']);
if ($show_filter) {
	echo elgg_view('river/filter', $vars);
}

// activity
$defaults = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
	'pagination_previous_text' => elgg_echo('newer'),
	'pagination_next_text' => elgg_echo('older'),
	'pagination_show_numbers' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

$entity_type = elgg_extract('entity_type', $vars, 'all');
if ($entity_type !== 'all') {
	$options['type'] = $entity_type;
	
	$entity_subtype = elgg_extract('entity_subtype', $vars);
	if (!empty($entity_subtype)) {
		$options['subtype'] = $entity_subtype;
	}
}

echo elgg_list_river($options);
