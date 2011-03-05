<?php
/**
 * Create a submit input button
 *
 * @package Elgg
 * @subpackage Core
 */

$vars['type'] = 'submit';
$vars['class'] = elgg_extract('class', $vars, 'elgg-button-submit');

echo elgg_view('input/button', $vars);