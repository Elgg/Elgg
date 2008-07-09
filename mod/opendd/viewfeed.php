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
	
	$entity = get_entity(get_input('feed_guid'));
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
		
	$body = elgg_view('opendd/profile', array('entity' => $entity));
	
	$ctx = stream_context_create(array(
	    'http' => array(
	        'timeout' => 1
	        )
	    )
	); 
	
	$data = file_get_contents($entity->feedurl);//, 0, $ctx);
	
	if ($data)
	{
		$opendd = ODD_Import($data);
		
		if ($opendd)
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
			
			$context = get_context();
			set_context('search');
			$body .= elgg_view_entity_list($objects, $cnt, $offset, $limit, false);
			set_context($context);
			
		}
		else
			$body .= elgg_echo('opendd:noopenddfound');
	}
	else
		$body .= sprintf(elgg_echo('opendd:nodata'), $http_response_header[0]);
	
	// Finally draw the page
	page_draw($vars['entity']->feedurl,elgg_view_layout('one_column', $body));
?>