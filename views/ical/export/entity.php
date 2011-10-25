<?php
/**
 * Elgg ICAL output of default object.
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$entity = $vars['entity'];

if ($entity instanceof Notable &&
	$entity->getCalendarStartTime() &&
	$entity->getCalendarEndTime()) {

	$timestamp = date("Ymd\THis\Z", $entity->getTimeCreated());
	$start = date("Ymd\THis\Z", $entity->getCalendarStartTime());
	$end = date("Ymd\THis\Z", $entity->getCalendarEndTime());
	$summary = $entity->title;
	$modified = date("Ymd\THis\Z", $entity->getTimeUpdated());

	echo <<< ICAL
BEGIN:VEVENT
DTSTAMP:$timestamp
DTSTART:$start
DTEND:$end
SUMMARY:$summary
LAST-MODIFIED:$modified
END:VEVENT

ICAL;

}
