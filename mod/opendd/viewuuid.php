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

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// The uuid to retrieve
	$uuid = get_input('uuid');
	
	// Fetch the UUID as an object
	$odd = opendd_fetch_uuid($uuid);
	
	$body = "";
	foreach ($odd as $o)
	{
		if ($o instanceof ODDMetaData)
		{
			if (($o->getAttribute('name') == 'renderedentity') && ($o->getAttribute('type')=='volatile'))
				$body = $o->getBody();
		}
	}
	
	if ($body=="")
	{
		$entity = opendd_odd_to_elgg($odd);
		$body = elgg_view_entity($entity, true);
	}
	
	$body = elgg_view_layout('one_column',$body);
		
	// Finally draw the page
	page_draw($uuid, $body);
?>