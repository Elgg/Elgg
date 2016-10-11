<?php
/**
 * Renders a <button type="reset">
 *
 * @uses $vars['text'] Text of the button
 */

$vars['type'] = 'reset';

if (!isset($vars['text'])) {
	$vars['text'] = elgg_echo('reset');
}

echo elgg_view('input/button', $vars);