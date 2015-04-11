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

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = "elgg-button-submit";

echo elgg_view('input/button', $vars);
