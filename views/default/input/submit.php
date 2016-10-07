<?php
/**
 * Create a submit input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 * @uses $vars['text']  Text of the submit button
 * @uses $vars['value'] Value of the submit input
 */

$vars['type'] = 'submit';

$vars['class'] = elgg_extract_class($vars, 'elgg-button-submit');

if (!isset($vars['text']) && isset($vars['value'])) {
	$vars['text'] = $vars['value'];
}

echo elgg_view('input/button', $vars);
