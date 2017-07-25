<?php
/**
 * Renders a <button type="submit">
 *
 * See input/button view for a full list of options
 *
 * @uses $vars['text'] Text of the button
 */

$vars['type'] = 'submit';

echo elgg_view('input/button', $vars);
