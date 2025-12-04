<?php
/**
 * Generic view for entity listing
 *
 * @uses $vars['getter']  (optional) different getter function to be used by elgg_list_entities()
 * @uses $vars['options'] array with additional listing options
 */

$defaults = [
	'no_results' => true,
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

if (empty($options['type']) && empty($options['subtype'])) {
	throw new \Elgg\Exceptions\InvalidArgumentException("Missing 'type' and 'subtype' in the listing options");
}

$getter = elgg_extract('getter', $vars);

echo elgg_list_entities($options, $getter);
