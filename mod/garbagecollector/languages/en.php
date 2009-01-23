<?php
	/**
	 * Elgg garbage collector language pack.
	 * 
	 * @package ElggGarbageCollector
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'garbagecollector:period' => 'How often should the Elgg garbage collector run?',
	
			'garbagecollector:weekly' => 'Once a week',
			'garbagecollector:monthly' => 'Once a month',
			'garbagecollector:yearly' => 'Once a year',
	
			'garbagecollector' => "GARBAGE COLLECTOR\n",
			'garbagecollector:done' => "DONE\n",
			'garbagecollector:optimize' => "Optimizing %s ",
	
			'garbagecollector:error' => "ERROR",
			'garbagecollector:ok' => "OK",
	
			'garbagecollector:gc:metastrings' => 'Cleaning up unlinked metastrings: ',
	
	);
					
	add_translation("en",$english);
?>