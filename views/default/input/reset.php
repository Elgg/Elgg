<?php
/**
 * Renders a <button type="reset">
 *
 * @uses $vars['text'] Text of the button
 */

$vars['type'] = 'reset';

echo elgg_view('input/button', $vars);
