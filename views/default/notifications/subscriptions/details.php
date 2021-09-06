<?php
/**
 * Displays subscription record details for extra settings
 *
 * @uses $vars['user_guid']   Subscriber
 * @uses $vars['entity_guid'] Target entity of the subscription
 */

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$user = get_entity((int) elgg_extract('user_guid', $vars));
$entity = get_entity((int) elgg_extract('entity_guid', $vars));

if (!$user instanceof \ElggUser || !$entity instanceof \ElggEntity) {
	throw new BadRequestException();
}

if (!$user->canEdit()) {
	throw new EntityPermissionsException();
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	throw new PageNotFoundException();
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:{$method}");
	$method_options[$label] = $method;
}

$detailed_subscriptions = [];
foreach ($entity->getSubscriptions($user->guid) as $subscription) {
	$parts = explode(':', $subscription->relationship);
	if (count($parts) > 2) {
		list(, $type, $subtype, $action, $method) = $parts;
		$detailed_subscriptions[$type][$subtype][$action][$method] = $method;
	}
}

$notification_events = elgg_get_notification_events();
foreach ($notification_events as $type => $subtypes) {
	foreach ($subtypes as $subtype => $actions) {
		foreach ($actions as $action => $handler) {
			// handler is the classname of the notification event handler
			// the classname is a subclass of \Elgg\Notifications\NotificationEventHandler
			if (!$handler::isConfigurableByUser()) {
				continue;
			}
			
			echo elgg_view_field([
				'#type' => 'checkboxes',
				'#label' => elgg_echo("notification:{$type}:{$subtype}:{$action}"),
				'#class' => 'elgg-subscription-details',
				'name' => "subscriptions[{$entity->guid}][notify:{$type}:{$subtype}:{$action}]",
				'options' => $method_options,
				'value' => $detailed_subscriptions[$type][$subtype][$action] ?? [],
				'align' => 'horizontal',
			]);
		}
	}
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'#class' => 'mtm',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'button',
			'#class' => 'float-alt',
			'text' => elgg_echo('notifications:subscriptions:details:reset'),
			'class' => ['elgg-button-delete', 'elgg-subscriptions-details-reset'],
		],
	],
]);
