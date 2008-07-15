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

	/**
	 * Initialise the opendd plugin.
	 * Register actions, set up menus
	 */
	function opendd_init()
	{
		global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('opendd'), $CONFIG->wwwroot . "pg/opendd/{$_SESSION['user']->username}",array(
				menu_item(elgg_echo('opendd:your'), $CONFIG->wwwroot."pg/opendd/{$_SESSION['user']->username}"),
				menu_item(elgg_echo('opendd:feeds'), $CONFIG->wwwroot."pg/opendd/{$_SESSION['user']->username}/feeds/"),
				menu_item(elgg_echo('opendd:manage'), $CONFIG->wwwroot . "pg/opendd/{$_SESSION['user']->username}/manage/"),
			),'opendd');
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('opendd','opendd_page_handler');
		
		// Register opendd url
		register_entity_url_handler('opendd_url','object','oddfeed');
		
		// Actions
		register_action("opendd/feed/subscribe",false, $CONFIG->pluginspath . "opendd/actions/opendd/feed/subscribe.php");
		register_action("opendd/feed/delete",false, $CONFIG->pluginspath . "opendd/actions/opendd/feed/delete.php");
		
		// Extend some views
		extend_view('css','opendd/css');
		
		// Register some widgets
		add_widget_type('opendd_friends_elsewhere',elgg_echo('opendd:widgets:elsewhere:title'), elgg_echo('opendd:widgets:elsewhere:description'));
		
		// Subscribe fields
		$CONFIG->opendd = array(
			'feedurl' => 'text',
		);
		
	}
	
	/**
	 * Group page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function opendd_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0]))
			set_input('username',$page[0]);
		
		if (isset($page[1]))
		{
			// See what context we're using
			switch($page[1])
			{		
				case "view" :
					if (isset($page[2]))
					{
						set_input('feed_guid', $page[2]);
						include($CONFIG->pluginspath . "opendd/viewfeed.php");
					}
				break;		
    			case "manage":  
   					include($CONFIG->pluginspath . "opendd/manage.php");
          		break;
          		case "feeds" :
					include($CONFIG->pluginspath . "opendd/feeds.php");
				break;
          		case "activity" :
          			if (isset($page[2]))
					{
						switch ($page[2])
						{
							case 'opendd' :
							default :
								set_input('view', 'odd');
								include($CONFIG->pluginspath . "opendd/index.php");
						}
					}
					break;
    			default:
    				include($CONFIG->pluginspath . "opendd/index.php");
			}
		}
		else
			include($CONFIG->pluginspath . "opendd/index.php");
	}
	
	/**
	 * Register a url to handle opendd feeds.
	 *
	 * @param ElggEntity $feed The feed object.
	 * @return string
	 */
	function opendd_url($feed) 
	{
		global $CONFIG;
		return $CONFIG->wwwroot . "pg/opendd/" . $feed->getOwnerEntity()->username . "/view/{$feed->guid}";
	}

	/**
	 * Return a list of feed urls for a given user.
	 *
	 * @param int $user_guid User in question
	 * @return array 
	 */
	function opendd_get_feed_urls($user_guid)
	{
		$feeds = array();
		
		$feed_entities = get_entities('object', 'oddfeed', $user_guid);
		if ($feed_entities)
		{
			foreach ($feed_entities as $feed)
				$feeds[] = $feed->feedurl;
		}
		
		if (count($feeds))
			return $feeds;
			
		return false;	
	}
	
	/**
	 * Fetch a given UUID.
	 *
	 * @param string $uuid The uuid
	 * @return ODDDocument
	 */
	function opendd_fetch_uuid($uuid)
	{
		$ctx = stream_context_create(array(
		    'http' => array(
		        'timeout' => 1
		        )
		    )
		); 
		
		$feed_data = ODD_Import(file_get_contents($uuid));//, 0, $ctx));
		if ($feed_data)
			return $feed_data;
		
		return NULL;
	}
	
	// Array of ODD objects mapped to uuid
	$uuid_array = array();
	
	// Array of ElggEntities mapped to uuid
	$elgg_array = array();
	
	/**
	 * Combines opendd_odd_to_elgg and opendd_fetch_uuid and fetch a single uuid and return an object or an array 
	 * suitable for the ElggRiverStatement Object.
	 *
	 * @param string $uuid The UUID
	 * @return mixed
	 */
	function opendd_fetch_to_elgg($uuid)
	{
		global $uuid_array, $elgg_array;
		
		if (!isset($uuid_array[$uuid])) 
			$uuid_array[$uuid] = opendd_fetch_uuid($uuid);
		if ((!isset($elgg_array[$uuid])) && (isset($uuid_array[$uuid])))
			$elgg_array[$uuid] = opendd_odd_to_elgg($uuid_array[$uuid]);
			
		if ($elgg_array[$uuid])
			return $elgg_array[$uuid];
			
		return false;
	}
	
	/**
	 * Construct an Elgg object out of a given element and its metadata (like import without doing any saving).
	 * This does not function if you are sending a relationship... this is a special case and is returned as an unchanged 
	 * ODDRelationship (since it is objects are not being saved and so guids are currently meaningless.)
	 * 
	 * TODO: Optimise so that it caches uuids (shared with other uuid cache)
	 *
	 * @param ODDDocument $element
	 */
	function opendd_odd_to_elgg(ODDDocument $element)
	{
		global $uuid_array, $elgg_array, $CONFIG;
		
		$count = $element->getNumElements();
		
		if ($count==1)
		{
			// Atomic component - relationship or metadata;
			$elements = $element->getElements();
			$e = $elements[0]; 
			if ($e instanceof ODDRelationship)
			{
				// Return statement object array
				$object = array();
				$object['subject'] = opendd_fetch_to_elgg($e->getAttribute('uuid1'));
				$object['relationship'] = $e->getAttribute('type');
				$object['object'] = opendd_fetch_to_elgg($e->getAttribute('uuid2'));
				
				return $object;
			}
			
			if ($e instanceof ODDMetaData)
			{
				$type = $e->getAttribute('type');
				$attr_name = $e->getAttribute('name');
				$attr_val = $e->getBody();
				
				$subject = NULL;
				switch ($type)
				{
					case 'annotation' : 
						$subject = new ElggAnnotation();
					break;
					case 'metadata' :
					default:
						$subject = new ElggMetaData();
					break;	
				}
				
				$subject->name = $attr_name;
				$subject->value = $attr_value;
				$subject->type = $type;
				$subject->time_created = $e->getAttribute('published');
				
				$object = array('subject' => $subject, 'object' => opendd_fetch_to_elgg($e->getAttribute('entity_uuid')));
				
				return $object;
			}
		}
		else
		{
			
			$tmp = array();
			// Go through the elements
			foreach ($element as $e)
			{
				$uuid = $e->getAttribute('uuid');
				
				// if entity then create
				if ($e instanceof ODDEntity) {
					$tmp[$uuid] = oddentity_to_elggentity($e);
					$tmp[$uuid]->setURL($CONFIG->url . "mod/opendd/viewuuid.php?uuid=" . urlencode($uuid));
				}
				
				// if metadata then add to entity
				if ($e instanceof ODDMetaData) {
					
					$entity_uuid = $e->getAttribute('entity_uuid');
					oddmetadata_to_elggextender($tmp[$entity_uuid], $e);
				}
				
			}
			
			foreach ($tmp as $t)
				return $t;
			
		}
	}
	
	/**
	 * ISSUES
	 * 
	 * - all entities need to be public on target
	 * 
	 */
	
	
	/**
	 * This function provides a river-like view for remote friend feeds from multiple sources.
	 * It will produce an aggregation of the feeds and render them in a similar way to get_river_entries();
	 * 
	 * TODO: How do we handle metadata in this instance? - It needs to refer to the object.
	 *
	 * @param array $feeds List of Opendd feed urls.
	 * @param int $limit Maximum results to process.
	 * @param int $offset The offset.
	 */
	function opendd_aggregate_remote_river(array $feeds, $limit = 10, $offset = 0)
	{	
		global $uuid_array, $elgg_array, $CONFIG;

		// if this not an array, turn it into one
		if (!is_array($feeds))
			$feeds = array($feeds);
			
		// ensure there are no duplicates
		$feeds = array_unique($feeds);
		
		$river = array();
		$opendd_elements = array();
		$opendd_published = array();
		
		// set start limit and offset
		$cnt = $limit; // Didn' cast to int here deliberately
		$off = $offset; // here too
		
		$ctx = stream_context_create(array(
		    'http' => array(
		        'timeout' => 1
		        )
		    )
		); 
		
		// Get feeds
		foreach ($feeds as $feed)
		{
			// Retrieve feed
			$feed_data = ODD_Import(file_get_contents($feed, 0, $ctx));
	
			if ($feed_data)
			{
				$elements = $feed_data->getElements();
				foreach ($elements as $e)
					$opendd_elements[] = $e; 	
			}
		}

		foreach ($opendd_elements as $k => $v)
			$opendd_published[$k] = $v->getPublishedAsTime();
		
		// Sort by date (desc)
		arsort($opendd_published);
		$sorted_odd_elements = array();

		foreach ($opendd_published as $k => $v)
			$sorted_odd_elements[] = $opendd_elements[$k];

		// Array of ODD objects mapped to uuid
		$uuid_array = array();
		
		// Array of ElggEntities mapped to uuid
		$elgg_array = array();
		
		$exit = true;
		
		do
		{
			if (!count($sorted_odd_elements))
				$exit = true;
			else
			{
				foreach ($sorted_odd_elements as $oddelement)
				{						
					$statement = NULL;
					
					// Valid ODD activity streams can only be relationships! 
					if ($oddelement instanceof ODDRelationship)
					{
						$uuid1 = $oddelement->getAttribute('uuid1');
						$uuid2 = $oddelement->getAttribute('uuid2');
			
						// Construct our new statement
						$subject = opendd_fetch_to_elgg($uuid1);
						$event = $oddelement->getAttribute('type');
						$object = opendd_fetch_to_elgg($uuid2);
						$time = $oddelement->getPublishedAsTime();

						$statement = new ElggRiverStatement($subject, $event, $object);
						
						// Work out class
						if ($object instanceof ElggEntity)
							$class = get_class($object);
						else if (count($object)==3)
							$class = 'ElggRelationship';
						else
							$class = get_class($object['subject']); 
							
	
					}
	
					// If no fatal errors while extracting the necessary data then continue
					if (($subject) && ($object) && ($event) && ($statement))
					{			
						$tam = "";
						// We have constructed the information 
						
						if ($object instanceof ElggEntity) {
							$subtype = $object->getSubtype();
						} else {
							$subtype = "";
						}
						if ($subtype == "widget") {
							$subtype = "widget/" . $object->handler;
						}
						
						if (!empty($subtype) && elgg_view_exists("river/{$subtype}/{$event}")) {
							$tam = elgg_view("river/{$subtype}/$event", array(
								'statement' => $statement
							));
						} else if (elgg_view_exists("river/$class/$event")) {
							$tam = elgg_view("river/$class/$event", array(
								'statement' => $statement
							));
							
						}
						
						if ((!empty($tam)) && (trim($tam)!="")) {
							$tam = elgg_view("river/wrapper",array(
										'entry' => $tam, 
										'time' => $time,
										'event' => $event,
										'statement' => $statement
							));
						}
						
						if ($tam)
						{
							$river[] = $tam;
							$cnt--;
						}
				
				
					}
					/*else
					{
						echo "$uuid2\n";
						print_r($object);
						print_r($statement); 
						
						die();
					}*/
				
					if ($cnt == 0 ) break; // crufty
					
					// Increase offset
					$off++;
				}
			}
			
		} while (
			($cnt > 0) &&
			(!$exit)
		);
		
		
		return $river;


	}

	
	// Make sure the groups initialisation function is called on initialisation
	register_elgg_event_handler('init','system','opendd_init');
?>