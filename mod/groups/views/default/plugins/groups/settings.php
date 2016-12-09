<?php
/**
 * Groups plugin settings
 */

$plugin = $vars['entity'];
/* @var ElggPlugin $plugin */

// set default value
if (!isset($plugin->hidden_groups)) {
	$plugin->hidden_groups = 'no';
}

// set default value
if (!isset($plugin->limited_groups)) {
	$plugin->limited_groups = 'no';
}

// set default value
if (!isset($plugin->allow_activity)) {
	$plugin->allow_activity = 'yes';
}

$fields = [
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('groups:allowhiddengroups'),
		'name' => 'params[hidden_groups]',
		'default' => 'no',
		'switch' => true,
		'value' => 'yes',
		'checked' => ($plugin->hidden_groups === 'yes'),
	],
	[
		'#type' => 'select',
		'#label' => elgg_echo('groups:whocancreate'),
		'name' => 'params[limited_groups]',
		'options_values' => [
			'no' => elgg_echo('LOGGED_IN'),
			'yes' => elgg_echo('admin')
		],
		'value' => $plugin->limited_groups,
	],
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('groups:allow_activity'),
		'name' => 'params[allow_activity]',
		'default' => 'no',
		'value' => 'yes',
		'checked' => ($plugin->allow_activity === 'yes'),
		'switch' => true,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}
