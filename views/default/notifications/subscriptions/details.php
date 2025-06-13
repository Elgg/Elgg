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
$details = [];
foreach ($notification_events as $type => $subtypes) {
	foreach ($subtypes as $subtype => $actions) {
		/* @var $handler \Elgg\Notifications\NotificationEventHandler */
		foreach ($actions as $action => $handler) {
			// can users configure this handler
			if (!$handler::isConfigurableByUser()) {
				continue;
			}
			
			// can this handler be configured for the current container
			if (!$handler::isConfigurableForEntity($entity)) {
				continue;
			}
			
			$label = elgg_echo("notification:{$type}:{$subtype}:{$action}");
			$details[$label] = elgg_view_field([
				'#type' => 'checkboxes',
				'#label' => $label,
				'#class' => 'elgg-subscription-details',
				'name' => "subscriptions[{$entity->guid}][notify:{$type}:{$subtype}:{$action}]",
				'options' => $method_options,
				'value' => $detailed_subscriptions[$type][$subtype][$action] ?? [],
				'align' => 'horizontal',
			]);
		}
	}
}

if (empty($details)) {
	echo elgg_view_no_results(elgg_echo('notifications:subscriptions:details:no_results'));
	return;
}

ksort($details, SORT_NATURAL | SORT_FLAG_CASE);
echo implode('', $details);

echo elgg_view_field([
	'#type' => 'fieldset',
	'#class' => 'mtm',
	'align' => 'horizontal',
	'justify' => 'right',
	'fields' => [
		[
			'#type' => 'button',
			'text' => elgg_echo('notifications:subscriptions:details:reset'),
			'class' => ['elgg-button-delete', 'elgg-subscriptions-details-reset'],
		],
	],
]);
