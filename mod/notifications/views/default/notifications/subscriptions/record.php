<?php
/**
 * Displays a subscription record with preference choices
 *
 * @tip for correct styling this view should be wrapped in an element with the class 'elgg-subscription-record'
 *      which is wrapped in an element with the class 'elgg-subscriptions'
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

$name = elgg_view_entity_url($entity);

$preferred_methods = [];
$detailed_subscriptions = [];

// This volatile data is stored during a query with a custom select option
$relationships_concat = $entity->getVolatileData('select:relationships');
if (isset($relationships_concat)) {
	$relationships = explode(',', $relationships_concat);
	foreach ($relationships as $relationship) {
		if (strpos($relationship, 'notify:') === false) {
			continue;
		}
		
		list (, $type, $subtype, $action, $method) = explode(':', $relationship);
		
		if (empty($subtype)) {
			$preferred_methods[$type] = $type;
		} else {
			$detailed_subscriptions[$type][$subtype][$action][$method] = $method;
		}
	}
} else {
	foreach ($methods as $method) {
		if ($entity->hasSubscription($user->guid, $method)) {
			$preferred_methods[$method] = $method;
		}
	}
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:{$method}");
	$method_options[$label] = $method;
}

echo '<div>';
echo elgg_format_element('div', ['class' => 'elgg-subscription-description'], elgg_view_image_block($icon, $name));
echo elgg_view_field([
	'#type' => 'checkboxes',
	'#class' => 'elgg-subscription-methods',
	'name' => "subscriptions[{$entity->guid}][notify]",
	'options' => $method_options,
	'value' => $preferred_methods,
	'align' => 'horizontal',
]);
echo '</div>';

echo '<div>';
$notification_events = elgg_get_notification_events();
foreach ($notification_events as $type => $subtypes) {
	foreach ($subtypes as $subtype => $actions) {
		foreach ($actions as $action) {
			echo elgg_view_field([
				'#type' => 'checkboxes',
				'#label' => elgg_echo("notification:{$type}:{$subtype}:{$action}"),
				'#class' => 'elgg-subscription-details',
				'name' => "subscriptions[{$entity->guid}][notify:{$type}:{$subtype}:{$action}]",
				'options' => $method_options,
				'value' => isset($detailed_subscriptions[$type][$subtype][$action]) ? $detailed_subscriptions[$type][$subtype][$action] : [],
				'align' => 'horizontal',
			]);
		}
	}
}
echo '</div>';
