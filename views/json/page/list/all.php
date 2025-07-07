<?php
/**
 * Generic view for entity listing
 *
 * @uses $vars['options'] array with additional listing options
 */

$defaults = [
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

if (empty($options['type']) && empty($options['subtype'])) {
	throw new \Elgg\Exceptions\InvalidArgumentException("Missing 'type' and 'subtype' in the listing options");
}

echo elgg_list_entities($options);
