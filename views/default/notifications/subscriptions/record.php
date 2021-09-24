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

elgg_require_js('notifications/subscriptions/record');
elgg_require_css('notifications/subscriptions/record');

$icon = elgg_view('output/img', [
	'src' => $entity->getIconURL('tiny'),
	'alt' => $entity->getDisplayName(),
]);

$preferred_methods = [];
$has_detailed_subscriptions = false;

// This volatile data is stored during a query with a custom select option
$relationships_concat = $entity->getVolatileData('select:relationships');
if (isset($relationships_concat)) {
	$relationships = explode(',', $relationships_concat);
	foreach ($relationships as $relationship) {
		if (strpos($relationship, 'notify:') === false) {
			continue;
		}
		
		$parts = explode(':', $relationship);
		if (count($parts) > 2) {
			$has_detailed_subscriptions = true;
		} else {
			$type = $parts[1];
			$preferred_methods[$type] = $type;
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

$container = elgg_format_element('div', ['class' => 'elgg-subscription-description'], elgg_view_image_block($icon, elgg_view_entity_url($entity)));
$container .= elgg_view_field([
	'#type' => 'checkboxes',
	'#class' => 'elgg-subscription-methods',
	'name' => "subscriptions[{$entity->guid}][notify]",
	'options' => $method_options,
	'value' => $preferred_methods,
	'align' => 'horizontal',
	'disabled' => $has_detailed_subscriptions,
]);

$icon_class = ['elgg-subscription-details-toggle'];
if ($has_detailed_subscriptions) {
	$icon_class[] = 'elgg-state-active';
}

$container .= elgg_view('output/url', [
	'href' => false,
	'text' => elgg_echo('settings'),
	'title' => elgg_echo('notifications:subscriptions:record:settings'),
	'icon' => 'chevron-down',
	'class' => $icon_class,
	'data-view' => elgg_http_add_url_query_elements('notifications/subscriptions/details', [
		'user_guid' => $user->guid,
		'entity_guid' => $entity->guid,
	]),
]);
$container .= elgg_view('output/url', [
	'href' => false,
	'text' => elgg_echo('settings'),
	'icon' => 'chevron-up',
	'class' => array_merge($icon_class, ['hidden']),
]);

echo elgg_format_element('div', ['class' => 'elgg-subscription-container'], $container);

// placeholder for the details
echo elgg_format_element('div', ['class' => 'elgg-subscription-container-details']);
