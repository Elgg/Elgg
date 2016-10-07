<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src']   Src of an image
 * @uses $vars['class'] Additional CSS class
 * @uses $vars['text']  Text to include between <button> tags
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-button');

$defaults = ['type' => 'button'];

$vars = array_merge($defaults, $vars);

switch ($vars['type']) {
	case 'submit':
		$vars['class'][] = 'elgg-button-submit';
		break;
	case 'reset':
		$vars['class'][] = 'elgg-button-cancel';
		break;
	case 'button':
	case 'image':
		break;
	default:
		$vars['type'] = 'button';
		break;
}

$text = elgg_extract('text', $vars, '');
unset($vars['text']);

echo elgg_format_element('button', $vars, $text);
