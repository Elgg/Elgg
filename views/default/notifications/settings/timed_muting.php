<?php
/**
 * Set a time window where you don't wish to receive notifications
 *
 * @uses $vars['entity'] the user to set the setings for
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$start = $user->getPrivateSetting('timed_muting_start');
$end = $user->getPrivateSetting('timed_muting_end');

$fields = [
	[
		'#type' => 'fieldset',
		
		'#help' => elgg_echo('usersettings:notifications:timed_muting:help'),
		'align' => 'horizontal',
		'fields' => [
			[
				'#type' => 'date',
				'#label' => elgg_echo('usersettings:notifications:timed_muting:start'),
				'name' => 'timed_muting_start',
				'value' => $start,
				'timestamp' => true,
			],
			[
				'#type' => 'date',
				'#label' => elgg_echo('usersettings:notifications:timed_muting:end'),
				'name' => 'timed_muting_end',
				'value' => $end,
				'timestamp' => true,
			],
		],
	],
];

if (!empty($start) && !empty($end) && $start < time() && $end > time()) {
	$fields[] = [
		'#html' => elgg_view_message('warning', elgg_echo('usersettings:notifications:timed_muting:warning'), ['title' => false]),
	];
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'#label' => elgg_echo('usersettings:notifications:timed_muting'),
	'#class' => 'ptl',
	'fields' => $fields,
]);
