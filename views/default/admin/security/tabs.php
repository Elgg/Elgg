<?php
/**
 * Admin helper view for tabs on the different security pages
 *
 * @uses $vars['selected'] the name of the selected tab
 */

$selected = elgg_extract('selected', $vars, 'settings');

$tabs = [
	[
		'name' => 'settings',
		'text' => elgg_echo('admin:security:settings'),
		'href' => elgg_generate_url('admin', [
			'segments' => 'security',
		]),
		'selected' => $selected === 'settings',
	],
	[
		'name' => 'information',
		'text' => elgg_echo('admin:security:information'),
		'href' => elgg_generate_url('admin', [
			'segments' => 'security/information',
		]),
		'selected' => $selected === 'information',
	],
];

echo elgg_view('navigation/tabs', [
	'tabs' => $tabs,
]);
