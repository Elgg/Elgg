<?php
/**
 * Generic view to set a notification preference for a specific purpose.
 * The action will handle the saving of the preferences, no need for your own handling
 *
 * @see \ElggUser::getNotificationSettings()
 * @see \ElggUser::setNotificationSetting()
 *
 * @uses $vars['entity']      The user for which to show/set the preference
 * @uses $vars['description'] The description of the notification setting
 * @uses $vars['purpose']     For which purpose is the notification setting used
 */

$user = elgg_extract('entity', $vars);
$description = elgg_extract('description', $vars);
$purpose = elgg_extract('purpose', $vars);
if (!$user instanceof \ElggUser || empty($description) || empty($purpose)) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:{$method}");
	$method_options[$label] = $method;
}

?>
<div>
	<div>
		<?= $description; ?>
	</div>
	<?php
	$value = array_keys(array_filter($user->getNotificationSettings($purpose)));
	echo elgg_view_field([
		'#type' => 'checkboxes',
		'name' => "notification_setting[{$purpose}]",
		'options' => $method_options,
		'default' => 0,
		'value' => $value,
		'align' => 'horizontal',
	]);
	?>
</div>
