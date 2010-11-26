<?php
/**
 * Elgg ICAL output of default object.
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$entity = $vars['entity'];

if (
	($entity instanceof Notable) &&
	($entity->getCalendarStartTime()) &&
	($entity->getCalendarEndTime())
)
{
?>
BEGIN:VEVENT
DTSTAMP:<?php echo date("Ymd\THis\Z", $entity->getTimeCreated());  ?>
DTSTART:<?php echo date("Ymd\THis\Z", $entity->getCalendarStartTime());  ?>
DTEND:<?php echo date("Ymd\THis\Z", $entity->getCalendarEndTime());  ?>
SUMMARY:<?php echo $event->title; ?>
LAST-MODIFIED:<?php echo date("Ymd\THis\Z", $entity->getTimeUpdated());  ?>
END:VEVENT
<?php
}
?>
	if (

	)