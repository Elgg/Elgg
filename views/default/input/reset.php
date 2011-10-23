<?php
/**
 * Create a reset input button
 *
 * @package Elgg
 * @subpackage Core
 * 
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-button-cancel {$vars['class']}";
} else {
	$vars['class'] = "elgg-button-cancel";
}

$vars['type'] = 'reset';

echo elgg_view('input/button', $vars);