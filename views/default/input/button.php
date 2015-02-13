<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src']   Src of an image
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = "elgg-button";

$defaults = ['type' => 'button'];

$vars = array_merge($defaults, $vars);

switch ($vars['type']) {
	case 'button':
	case 'reset':
	case 'submit':
	case 'image':
		break;
	default:
		$vars['type'] = 'button';
		break;
}

// blank src if trying to access an offsite image. @todo why?
if (isset($vars['src']) && strpos($vars['src'], elgg_get_site_url()) === false) {
	$vars['src'] = "";
}

echo elgg_format_element('input', $vars);
