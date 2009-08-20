<?php
	/**
	 * Elgg Cron trigger.
	 * When enabled this plugin provides "poor man's cron" functionality to trigger elgg cron scripts without the need
	 * to install the cron script.
	 * 
	 * Note, this is a substitute and not a replacement for the cron script. It is recommended that you use the cron script
	 * where possible.
	 * 
	 * @package ElggCronTrigger
	 * @author Curverider Ltd
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the plugin.
	 *
	 */
	function crontrigger_init()
	{
		register_elgg_event_handler('shutdown', 'system', 'crontrigger_shutdownhook');
	}
	
	function crontrigger_trigger($period) { trigger_plugin_hook('cron', $period); }
	
	function crontrigger_minute() { crontrigger_trigger('minute'); }
		
	function crontrigger_fiveminute() { crontrigger_trigger('fiveminute'); }
	
	function crontrigger_fifteenmin() { crontrigger_trigger('fifteenmin'); }
	
	function crontrigger_halfhour() { crontrigger_trigger('halfhour'); }
	
	function crontrigger_hourly() { crontrigger_trigger('hourly'); }
	
	function crontrigger_daily() { crontrigger_trigger('daily'); }
	
	function crontrigger_weekly() { crontrigger_trigger('weekly'); }
	
	function crontrigger_monthly() { crontrigger_trigger('monthly'); }
	
	function crontrigger_yearly() { crontrigger_trigger('yearly'); }
	
	/**
	 * Call cron hooks after a page has been displayed (so user won't notice any slowdown).
	 * 
	 * It uses a mod of now and needs someone to view the page within a certain time period
	 *
	 */
	function crontrigger_shutdownhook()
	{
		global $CONFIG;
		
		$minute = 60;
		$fiveminute = $minute*5;
		$fifteenmin = $minute*15;
		$halfhour = $minute*30;
		$hour = 3600;
		$day = $hour*24;
		$week = $day * 7;
		$month = $week * 4;
		$year = $month * 12;
		
		$now = time();
		
		ob_start();
		run_function_once('crontrigger_minute', $now - $minute);
		run_function_once('crontrigger_fiveminute', $now - $fiveminute);
		run_function_once('crontrigger_fifteenmin', $now - $fifteenmin);
		run_function_once('crontrigger_halfhour', $now - $halfhour);
		run_function_once('crontrigger_hourly', $now - $hour);
		run_function_once('crontrigger_daily', $now - $day);
		run_function_once('crontrigger_weekly', $now - $week);
		run_function_once('crontrigger_monthly', $now - $month);
		run_function_once('crontrigger_yearly', $now - $year);
		ob_clean();
	}
	
	
	// Initialise plugin
	register_elgg_event_handler('init','system','crontrigger_init');
?>