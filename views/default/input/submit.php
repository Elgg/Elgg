<?php
/**
 * Create a submit input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['type'] = 'submit';

if (!isset($vars['class'])) {
	$vars['class'] = array('elgg-button-submit');
} elseif (!is_array($vars['class'])) {
	$vars['class'] = array($vars['class']);
	$vars['class'][] = 'elgg-button-submit';
}

echo elgg_view('input/button', $vars);