<?php
/**
 * Displays a subscription record with preference choices
 *
 * @uses $vars['user']   Subscriber
 * @uses $vars['entity'] Target entity of the subscription
 */
$user = elgg_extract('user', $vars);
$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggEntity || !$user instanceof ElggUser || !$user->canEdit()) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$icon = elgg_view('output/img', [
	'src' => $entity->getIconURL('tiny'),
	'alt' => $entity->getDisplayName(),
]);

$name = $entity->getDisplayName();

$preferred_methods = [];

// This volatile data is stored during a query with a custom select option
$relationships_concat = $entity->getVolatileData('select:relationships');

if (isset($relationships_concat)) {
	$relationships = explode(',', $relationships_concat);
	foreach ($relationships as $relationship) {
		if (0 === strpos($relationship, 'notify')) {
			$method = str_replace('notify', '', $relationship);
			$preferred_methods[$method] = $method;
		}
	}
} else {
	foreach ($methods as $method) {
		if (check_entity_relationship($user->guid, "notify{$method}", $entity->guid)) {
			$preferred_methods[$method] = $method;
		}
	}
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:$method");
	$method_options[$label] = $method;
}
?>
<div class="elgg-subscription-record">
	<div class="elgg-subscription-description">
		<?= elgg_view_image_block($icon, $name) ?>
	</div>
	<?php
	echo elgg_view_input('checkboxes', [
		'name' => "subscriptions[$entity->guid]",
		'options' => $method_options,
		'value' => $preferred_methods,
		'align' => 'horizontal',
		'field_class' => 'elgg-subscription-methods',
	]);
	?>
</div>
