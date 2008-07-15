<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	
//include ("../../../../../../engine/start.php");

//$vars['entity'] = get_entity(80);

	$owner = page_owner_entity();
	$limit = 8;
	
	if ($vars['entity']->limit)
		$limit = $vars['entity']->limit;
		
	$river = opendd_aggregate_remote_river($vars['entity']->feeds, $limit, $offset);

	
	if ($river)
		echo elgg_view('river/dashboard', array('river' => $river));
	else
		echo elgg_echo("opendd:noriver");
?>