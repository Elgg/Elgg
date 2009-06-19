<?php

	/**
	 * Elgg ICAL output pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
