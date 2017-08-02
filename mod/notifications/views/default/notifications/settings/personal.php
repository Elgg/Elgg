<?php
/**
 * Configure global notification settings
 *
 * @uses $vars['user'] Subscriber
 */
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:$method");
	$method_options[$label] = $method;
}
?>
<div class="elgg-subscription-record">
	<div class="elgg-subscription-description">
		<?= elgg_echo('notifications:subscriptions:personal:description') ?>
	</div>
	<?php
	$value = array_keys(array_filter($user->getNotificationSettings()));
	echo elgg_view_field([
		'#type' => 'checkboxes',
		'#class' => 'elgg-subscription-methods',
		'name' => 'personal',
		'options' => $method_options,
		'default' => false,
		'value' => $value,
		'align' => 'horizontal',
	]);
	?>
</div>
