<?php

/**
 * Renders a <button>
 *
 * @uses $vars['type']  Button type (submit|reset|image)
 * @uses $vars['class'] Additional CSS class
 * @uses $vars['text']  Text to include between <button> tags
 * @uses $vars['icon']  Optional icon name
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-button');

if (!isset($vars['text']) && isset($vars['value'])) {
	// Keeping this to ease the transition to 3.0
	$vars['text'] = elgg_extract('value', $vars);
}

$type = elgg_extract('type', $vars, 'button', false);
$vars['type'] = $type;

$text = elgg_extract('text', $vars);
unset($vars['text']);

$text = elgg_format_element('span', [
	'class' => 'elgg-button-label',
], $text);

$icon = elgg_extract('icon', $vars, '');
unset($vars['icon']);

if ($icon && !preg_match('/^</', $icon)) {
	$icon = elgg_view_icon($icon, [
		'class' => 'elgg-button-icon',
	]);
}

switch ($type) {
	case 'submit':
		$vars['class'][] = 'elgg-button-submit';
		break;

	case 'reset':
		$vars['class'][] = 'elgg-button-cancel';
		break;
}

echo elgg_format_element('button', $vars, $icon . $text);
