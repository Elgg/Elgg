<?php

/* @var $custom_index \ElggPlugin */
$custom_index = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('custom_index:settings:about'),
	'name' => 'params[about]',
	'value' => $custom_index->about,
]);

$modules = [
	'about' => '',
	'register' => '',
	'login' => '',
	'activity' => 'activity',
	'blog' => 'blog',
	'bookmarks' => 'bookmarks',
	'file' => 'file',
	'groups' => 'groups',
	'users' => '',
];

foreach ($modules as $module => $plugin) {
	if (!empty($plugin) && !elgg_is_active_plugin($plugin)) {
		continue;
	}
	
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('custom_index:settings:enable_module', [$module]),
		'name' => "params[module_{$module}_enabled]",
		'checked' => (bool) $custom_index->{"module_{$module}_enabled"},
		'switch' => true,
		'default' => 0,
		'value' => 1,
	]);
}
