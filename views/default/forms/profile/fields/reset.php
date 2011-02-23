<?php
/**
 * Reset profile fields form
 */

$params = array(
	'value' => elgg_echo('profile:resetdefault'),
	'class' => 'elgg-button elgg-button-action elgg-state-disabled',
);
echo elgg_view('input/submit', $params);
