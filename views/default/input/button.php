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
	
	elgg_deprecated_notice('Only providing a value to a button is deprecated, please also provide a text: ' . $vars['value'], '5.0');
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

$text = (string) elgg_extract('text', $vars);
unset($vars['text']);

if (!isset($vars['aria-label']) && !isset($vars['aria-labelledby']) && !isset($vars['title']) && empty(elgg_strip_tags($text))) {
	elgg_log('An input/button should have a discernible text (text, title, aria-label or aria-labelledby)', 'NOTICE');
}

if (!elgg_is_empty($text)) {
	$text = elgg_format_element('span', [
		'class' => 'elgg-button-label',
	], $text);
}

$icon = (string) elgg_extract('icon', $vars);
unset($vars['icon']);

if (!elgg_is_empty($icon) && !str_starts_with($icon, '<')) {
	$icon = elgg_view_icon($icon, [
		'class' => 'elgg-button-icon',
	]);
}

$icon_alt = (string) elgg_extract('icon_alt', $vars);
unset($vars['icon_alt']);

if (!elgg_is_empty($icon_alt) && !str_starts_with($icon_alt, '<')) {
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
