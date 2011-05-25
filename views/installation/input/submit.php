<?php
/**
 * Create a submit input button
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 */

$vars['type'] = 'submit';

echo elgg_view('input/button', $vars);