<?php

/**
 * Renders a <button>
 *
 * @uses $vars['type']    Button type (submit|reset|image)
 * @uses $vars['class']   Additional CSS class
 * @uses $vars['text']    Text to include between <button> tags
 * @uses $vars['icon']    Optional icon name
 * @uses $vars['confirm'] Confirmation dialog text | (bool) true
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-button');

if (!isset($vars['text']) && isset($vars['value'])) {
	// Keeping this to ease the transition to 3.0
	$vars['text'] = elgg_extract('value', $vars);
}

if (!empty($vars['confirm'])) {
	$vars['data-confirm'] = elgg_extract('confirm', $vars);
	
	// if (bool) true use defaults
	if ($vars['data-confirm'] === true) {
		$vars['data-confirm'] = elgg_echo('question:areyousure');
	}
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

$icon_alt = elgg_extract('icon_alt', $vars, '');
unset($vars['icon_alt']);

if ($icon_alt && !preg_match('/^</', $icon_alt)) {
	$icon_alt = elgg_view_icon($icon_alt, [
		'class' => 'elgg-button-icon-alt',
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

echo elgg_format_element('button', $vars, $icon . $text . $icon_alt);
