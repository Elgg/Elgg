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
	
	global $CONFIG;
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$view = get_input("view");

	$title = sprintf(elgg_echo("opendd:your"),page_owner_entity()->name);
	
	$opendd = get_river_entries_as_opendd(page_owner(), "", $limit, $offset);

	if ($view=='odd')
	{
		// Slightly crufty hack
		header ("Content-Type: text/xml");
		echo $opendd;
		
	}
	else
	{
		$objects = array();
				
		$cnt = 0;
		foreach ($opendd as $obj)
		{
			if (($cnt >= $offset) && ($cnt < $offset + $limit))
			{
				$tmp = new ElggObject();
				$tmp->subtype = strtolower(get_class($obj)); 
				
				$attr = $obj->getAttributes();
				foreach ($attr as $k => $v)
				{
					$key = 'opendd:' . $k;
					$tmp->$key = $v;
				}
				
				$key = 'opendd:body';
				$tmp->$key = $obj->getBody();
				
				$objects[] = $tmp;
			}
			
			$cnt++;
		}
		
		$body = elgg_view_title($title);
		
		$context = get_context();
		set_context('search');
		$body .= elgg_view_entity_list($objects, $cnt, $offset, $limit, false);
		set_context($context);
		
		// Add logo link
		$body .= elgg_view('opendd/link_logo', array('feed_url' => $CONFIG->url . "pg/opendd/". page_owner_entity()->username ."/activity/opendd"));
		
		// Turn off rss view & default odd view
		global $autofeed; $autofeed = false;
		
		// Point to my activity in odd
		extend_view('metatags', 'opendd/metatags');
		
		$body = elgg_view_layout('one_column',$body);
		
		// Finally draw the page
		page_draw($title, $body);
	}
?>