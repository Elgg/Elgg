<?php
	/**
	 * Elgg ICAL output of default object.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
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