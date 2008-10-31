<?php
	/**
	 * Elgg garbage collector.
	 * 
	 * @package ElggGarbageCollector
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the plugin.
	 *
	 */
	function garbagecollector_init()
	{
		$period = get_plugin_setting('period','garbagecollector');
		switch ($period)
		{
			case 'weekly':
			case 'monthly' :
			case 'yearly' : 
			break;
			default: $period = 'monthly';
		}
		
		// Register cron hook
		register_plugin_hook('cron', $period, 'garbagecollector_cron');
	}
	
	/**
	 * Cron job
	 *
	 */
	function garbagecollector_cron($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		$resulttext = elgg_echo('garbagecollector');
		
		// Garbage collect metastrings
		$resulttext .= elgg_echo('garbagecollector:gc:metastrings');
		
		if (delete_orphaned_metastrings()!==false) {
			$resulttext .= elgg_echo('garbagecollector:ok');
		} else
			$resulttext .= elgg_echo('garbagecollector:error');
			
		$resulttext .= "\n";
		
		// Now we optimize all tables
		$tables = get_db_tables();
		foreach ($tables as $table) {
			$resulttext .= sprintf(elgg_echo('garbagecollector:optimize'), $table);
			
			if (optimize_table($table)!==false)
				$resulttext .= elgg_echo('garbagecollector:ok');
			else
				$resulttext .= elgg_echo('garbagecollector:error');

			$resulttext .= "\n";
		}
			
		$resulttext .= elgg_echo('garbagecollector:done');
			
		return $returnvalue . $resulttext;
	}
	
	// Initialise plugin
	register_elgg_event_handler('init','system','garbagecollector_init');
?>