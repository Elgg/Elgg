<?php
/**
 * User settings for notifications.
 */
$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);
$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
$notification_settings = $user->getNotificationSettings();
// Loop through options
foreach ($NOTIFICATION_HANDLERS as $k => $v) {
	echo elgg_view_field([
		'#type' => 'radio',
		'#label' => elgg_echo("notification:method:$k"),
		'name' => "method[$k]",
		'value' => $notification_settings[$k] ? 'yes' : 'no',
		'options' => [
			elgg_echo('option:yes') => 'yes',
			elgg_echo('option:no') => 'no',
		],
	]);
}
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);