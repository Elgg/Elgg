<?php
/**
 * Elgg ICAL output pageshell
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

header("Content-Type: text/calendar");

echo $vars['body'];
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Curverider Ltd//NONSGML Elgg <?php echo get_version(true); ?>//EN
<?php echo $vars['body']; ?>
END:VCALENDAR
