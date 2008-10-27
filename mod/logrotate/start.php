<?php
	/**
	 * Elgg log rotator.
	 * 
	 * @package ElggLogRotate
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
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
		if (!archive_log())
			$resulttext = elgg_echo("logrotate:lognotrotated");
			
		return $returnvalue . $resulttext;
	}
	
	// Initialise plugin
	register_elgg_event_handler('init','system','logrotate_init');
?>