<?php
/**
 * Create a hidden data field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 */

$vars['type'] = 'hidden';

echo elgg_format_element('input', $vars);
