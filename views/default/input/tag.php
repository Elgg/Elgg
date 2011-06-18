<?php
/**
 * Elgg tag input
 *
 * Accepts a single tag value
 *
 * @uses $vars['value'] The default value for the tag
 */

$defaults = array(
	'class' => 'elgg-input-tag',
	'disabled' => FALSE,
);

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);