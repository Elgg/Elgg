<?php
	/**
	 * Elgg log rotator.
	 * 
	 * @package ElggLogRotate
	 * @author Curverider Ltd
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the plugin.
	 *
	 */
	function logrotate_init()
	{
		$period = get_plugin_setting('period','logrotate');
		switch ($period)
		{
			case 'weekly':
			case 'monthly' :
			case 'yearly' : 
			break;
			default: $period = 'monthly';
		}
		
		// Register cron hook
		register_plugin_hook('cron', $period, 'logrotate_cron');
	}
	
	/**
	 * Trigger the log rotation.
	 *
	 */
	function logrotate_cron($hook, $entity_type, $returnvalue, $params)
	{
		$resulttext = elgg_echo("logrotate:logrotated");
		
		$day = 86400;
		
		$offset = 0;
		$period = get_plugin_setting('period','logrotate');
		switch ($period)
		{
			case 'weekly': $offset = $day * 7; break;
			case 'yearly' : $offset = $day * 365; break;
			case 'monthly' :  // assume 28 days even if a month is longer. Won't cause data loss.
			default: $offset = $day * 28;;
		}
		
		if (!archive_log($offset))
			$resulttext = elgg_echo("logrotate:lognotrotated");
			
		return $returnvalue . $resulttext;
	}
	
	// Initialise plugin
	register_elgg_event_handler('init','system','logrotate_init');
?>