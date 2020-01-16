<?php
/**
 * Create a hidden data field
 *
 * @uses $vars['value'] The current value, if any
 */

$vars['type'] = 'hidden';

echo elgg_format_element('input', $vars);
