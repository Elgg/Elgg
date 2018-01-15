<?php
/**
 * Elgg tag input
 * Displays a tag input field
 *
 * @uses $vars['disabled']
 * @uses $vars['class']    Additional CSS class
 * @uses $vars['value']    Array of tags or a string
 * @uses $vars['entity']   Optional. Entity whose tags are being displayed (metadata ->tags)
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-tags');

$defaults = [
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'type' => 'text'
];

if (isset($vars['entity'])) {
	$defaults['value'] = elgg_extract('entity', $vars)->tags;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

if (is_array($vars['value'])) {
	$tags = [];

	foreach ($vars['value'] as $tag) {
		if (is_string($tag)) {
			$tags[] = $tag;
		} else {
			$tags[] = $tag->value;
		}
	}

	$vars['value'] = implode(", ", $tags);
}

echo elgg_format_element('input', $vars);
