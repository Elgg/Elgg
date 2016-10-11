<?php
/**
 * Renders a <button type="submit">
 *
 * See input/button view for a full list of options
 * 
 * @uses $vars['text'] Text of the button
 */

$vars['type'] = 'submit';

if (!isset($vars['text']) && isset($vars['value'])) {
	// Keeping this to ease the transition to 3.0
	$vars['text'] = $vars['value'];
}

echo elgg_view('input/button', $vars);
