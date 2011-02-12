<?php
/**
 * Elgg long text input
 * Displays a long text input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any - will be html encoded
 * @uses $vars['disabled'] Is the input field disabled?
 */

$defaults = array(
	'class' => 'elgg-input-longtext',
);

$vars = array_merge($defaults, $vars);

echo elgg_view('input/plaintext', $vars);
