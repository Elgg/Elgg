<?php
/**
 * Elgg ICAL output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$site = elgg_get_site_entity();

header("Content-Type: text/calendar");
header('Content-Disposition: attachment; filename="'.$vars['title'].'.ics"');

?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Elgg//NONSGML <?php echo $site->name; ?>//EN
<?php echo $vars['body']; ?>
END:VCALENDAR
