<?php
/**
 * Plain text version of delayed email combined email
 *
 * @uses $vars['recipient']         the recipient of the mail
 * @uses $vars['notifications']     all the notifications for the given interval
 * @uses $vars['delivery_interval'] the delivery interval
 */

use Elgg\Notifications\Notification;

$notifications = (array) elgg_extract('notifications', $vars);
$recipient = elgg_extract('recipient', $vars);
if (empty($notifications) || !$recipient instanceof ElggEntity) {
	return;
}

// notification listing
// sort by content type
$sorted = [];

/* @var $notification Notification */
foreach ($notifications as $index => $notification) {
	/* @var $event \Elgg\Notifications\NotificationEvent */
	$event = elgg_extract('event', $notification->params);
	
	$category = 'other';
	
	$object = $event->getObject();
	if (empty($object)) {
		continue;
	}
	
	$entity = false;
	if ($object instanceof ElggEntity) {
		$entity = $object;
	} elseif ($object instanceof ElggAnnotation) {
		$entity = $object->getEntity();
	}
	
	if ($entity instanceof ElggEntity && $recipient instanceof ElggUser && !has_access_to_entity($entity, $recipient)) {
		// user no longer has access to entity
		continue;
	}
	
	if ($entity instanceof ElggEntity) {
		if (elgg_language_key_exists("collection:{$entity->type}:{$entity->subtype}")) {
			$category = "collection:{$entity->type}:{$entity->subtype}";
		} elseif (elgg_language_key_exists("item:{$entity->type}:{$entity->subtype}")) {
			$category = "item:{$entity->type}:{$entity->subtype}";
		}
	}
	
	if (!array_key_exists($category, $sorted)) {
		$sorted[$category] = [];
	}
	
	$sorted[$category][$object->getTimeCreated() . '_' . $index] = $notification;
}

$unknowns = elgg_extract('other', $sorted, []);
unset($sorted['other']);

ksort($sorted);

if (!empty($unknowns)) {
	// add the rest to the end of the list
	$sorted['other'] = $unknowns;
}

if (empty($sorted)) {
	// can happen if all notification objects have been removed
	return;
}

$output = elgg_echo('notifications:delayed_email:body:intro') . PHP_EOL . PHP_EOL;

foreach ($sorted as $category => $sorted_notifications) {
	uksort($sorted_notifications, 'strnatcasecmp');
	
	$output .= elgg_echo($category, [], $recipient->language) . PHP_EOL;
	
	/* @var $notification Notification */
	foreach ($sorted_notifications as $notification) {
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = elgg_extract('event', $notification->params);
		
		$output .= ' - ';
		$output .= $notification->summary ?: $notification->subject;
		
		$object = $event->getObject();
		$entity = false;
		if ($object instanceof ElggEntity) {
			$entity = $object;
		} elseif ($object instanceof ElggAnnotation) {
			$entity = $object->getEntity();
		}
		
		if ($entity instanceof ElggEntity) {
			$output .= ' ' . $object->getURL();
		}
		
		$output .= PHP_EOL;
	}
	
	$output .= PHP_EOL . PHP_EOL;
}

if (empty($output)) {
	return;
}

// add salutation and sign-off
echo elgg_view('notifications/body', [
	'body' => $output,
	'recipient' => $recipient,
]);
