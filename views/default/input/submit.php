<?php
/**
 * Create a submit input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] CSS class that replaces elgg-button-submit
 */

$vars['type'] = 'submit';
$vars['class'] = elgg_extract('class', $vars, 'elgg-button-submit');

echo elgg_view('input/button', $vars);