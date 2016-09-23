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

$vars['class'] = elgg_extract_class($vars, 'elgg-button-submit');

echo elgg_view('input/button', $vars);
