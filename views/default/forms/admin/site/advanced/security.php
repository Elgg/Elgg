<?php
/**
 * Advanced site settings, site section.
 */

$strength = _elgg_get_site_secret_strength();
$current_strength = elgg_echo('site_secret:current_strength');
$strength_text = elgg_echo("site_secret:strength:$strength");
$strength_msg = elgg_echo("site_secret:strength_msg:$strength");

$body = '<p>' . elgg_echo('admin:site:secret:intro') . '</p>';
$body .= elgg_view_module('main', "$current_strength: $strength_text", $strength_msg, [
	'class' => ($strength != 'strong') ? 'elgg-message elgg-state-error' : 'elgg-message elgg-state-success',
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:site:secret:regenerate'),
	'value' => 1,
	'name' => 'regenerate_site_secret',
	'#help' => elgg_echo('admin:site:secret:regenerate:help'),
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:security'), $body, ['id' => 'elgg-settings-advanced-security']);
