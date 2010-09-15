<?php
/**
 * Elgg cron library.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Initialisation
 *
 */
function cron_init() {
	// Register a pagehandler for cron
	register_page_handler('cron','cron_page_handler');
	
	// register a hook for Walled Garden public pages
	register_plugin_hook('public_pages', 'walled_garden', 'cron_public_pages');
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

function cron_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'pg/cron/minute';
	$return_value[] = 'pg/cron/fiveminute';
	$return_value[] = 'pg/cron/fifteenmin';
	$return_value[] = 'pg/cron/halfhour';
	$return_value[] = 'pg/cron/hourly';
	$return_value[] = 'pg/cron/daily';
	$return_value[] = 'pg/cron/weekly';
	$return_value[] = 'pg/cron/monthly';
	$return_value[] = 'pg/cron/yearly';
	$return_value[] = 'pg/cron/reboot';
	
	return $return_value;
}

// Register a startup event
register_elgg_event_handler('init','system','cron_init');