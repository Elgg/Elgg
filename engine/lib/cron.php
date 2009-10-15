<?php
/**
 * Elgg cron library.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/** The cron exception. */
class CronException extends Exception {}

/**
 * Initialisation
 *
 */
function cron_init() {
	// Register a pagehandler for cron
	register_page_handler('cron','cron_page_handler');
}

/**
 * Cron handler for redirecting pages.
 *
 * @param unknown_type $page
 */
function cron_page_handler($page) {
	global $CONFIG;

	if ($page[0]) {
		switch (strtolower($page[0])) {
			case 'minute' :
			case 'fiveminute' :
			case 'fifteenmin' :
			case 'halfhour' :
			case 'hourly' :
			case 'daily'  :
			case 'weekly' :
			case 'monthly':
			case 'yearly' :
			case 'reboot' :
				set_input('period', $page[0]);
				break;
			default :
				throw new CronException(sprintf(elgg_echo('CronException:unknownperiod'), $page[0]));
		}

		// Include cron handler
		include($CONFIG->path . "engine/handlers/cron_handler.php");
	} else {
		forward();
	}
}

// Register a startup event
register_elgg_event_handler('init','system','cron_init');