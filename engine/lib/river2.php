<?php

	/**
	 * Elgg river 2.0.
	 * Functions for listening for and generating the river separately from the system log.
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	/**
	 * Adds an item to the river.
	 *
	 * @param string $view The view that will handle the river item (must exist)
	 * @param string $action_type An arbitrary one-word string to define the action (eg 'comment', 'create')
	 * @param int $subject_guid The GUID of the entity doing the action
	 * @param int $object_guid The GUID of the entity being acted upon
	 * @param int $access_id The access ID of the river item (default: same as the object) 
	 * @param int $posted The UNIX epoch timestamp of the river item (default: now)
	 * @return true|false Depending on success
	 */
		function add_to_river(
								$view,
								$action_type,
								$subject_guid,
								$object_guid,
								$access_id = "",
								$posted = 0
							  ) {
							  	
			// Sanitise variables
				if (!elgg_view_exists($view)) return false;
				if (!($subject = get_entity($subject_guid))) return false;
				if (!($object = get_entity($object_guid))) return false;
				if (empty($action_type)) return false;
				if ($posted == 0) $posted = time();
				if ($access_id === "") $access_id = $object->access_id;
				
				$type = $object->getType();
				$subtype = $object->getSubtype();
				
				$action_type = sanitise_string($action_type);
				
			// Load config
				global $CONFIG;
				
			// Attempt to save river item; return success status
				return insert_data("insert into {$CONFIG->dbprefix}river " .
										" set type = '{$type}', " .
										" subtype = '{$subtype}', " .
										" action_type = '{$action_type}', " .
										" access_id = {$access_id}, " .
										" view = '{$view}', " .
										" subject_guid = {$subject_guid}, " .
										" object_guid = {$object_guid}, " .
										" posted = {$posted} ");
							  	
		}
		
	/**
	 * Removes all items relating to a particular acting entity from the river
	 *
	 * @param int $subject_guid The GUID of the entity
	 * @return true|false Depending on success
	 */
		function remove_from_river_by_subject(
									$subject_guid
											) {
			
			// Sanitise
				$subject_guid = (int) $subject_guid;
				
			// Load config
				global $CONFIG;
				
			// Remove
				return delete_data("delete from {$CONFIG->dbprefix}river where subject_guid = {$subject_guid}");
												
		}
		
	/**
	 * Removes all items relating to a particular entity being acted upon from the river
	 *
	 * @param int $object_guid The GUID of the entity
	 * @return true|false Depending on success
	 */
		function remove_from_river_by_object(
									$object_guid
											) {
			
			// Sanitise
				$object_guid = (int) $object_guid;
				
			// Load config
				global $CONFIG;
				
			// Remove
				return delete_data("delete from {$CONFIG->dbprefix}river where object_guid = {$object_guid}");
												
		}

	/**
	 * Sets the access ID on river items for a particular object
	 *
	 * @param int $object_guid The GUID of the entity
	 * @param int $access_id The access ID
	 * @return true|false Depending on success
	 */
		function update_river_access_by_object(
									$object_guid, $access_id
											) {
			
			// Sanitise
				$object_guid = (int) $object_guid;
				$access_id = (int) $access_id;
				
			// Load config
				global $CONFIG;
				
			// Remove
				return update_data("update {$CONFIG->dbprefix}river set access_id = {$access_id} where object_guid = {$object_guid}");
												
		}
		
	/**
	 * Retrieves items from the river. All parameters are optional.
	 *
	 * @param int|array $subject_guid Acting entity to restrict to. Default: all
	 * @param int|array $object_guid Entity being acted on to restrict to. Default: all
	 * @param string $subject_relationship If set to a relationship type, this will use $subject_guid as the starting point and set the subjects to be all users this entity has this relationship with (eg 'friend'). Default: blank
	 * @param string $type The type of entity to restrict to. Default: all
	 * @param string $subtype The subtype of entity to restrict to. Default: all
	 * @param string $action_type The type of river action to restrict to. Default: all
	 * @param int $limit The number of items to retrieve. Default: 20
	 * @param int $offset The page offset. Default: 0
	 * @param int $posted_min The minimum time period to look at. Default: none
	 * @param int $posted_max The maximum time period to look at. Default: none
	 * @return array|false Depending on success
	 */
		function get_river_items(
							$subject_guid = 0,
							$object_guid = 0,
							$subject_relationship = '',
							$type = '',
							$subtype = '',
							$action_type = '',
							$limit = 20,
							$offset = 0,
							$posted_min = 0,
							$posted_max = 0
                         ) { 
                         	
            // Get config
            	global $CONFIG;
                         	
			// Sanitise variables
				if (!is_array($subject_guid)) {
					$subject_guid = (int) $subject_guid;
				} else {
					foreach($subject_guid as $key => $temp) {
						$subject_guid[$key] = (int) $temp;
					}
				}
                if (!is_array($object_guid)) {
					$object_guid = (int) $object_guid;
				} else {
					foreach($object_guid as $key => $temp) {
						$object_guid[$key] = (int) $temp;
					}
				}
				if (!empty($type)) $type = sanitise_string($type);
				if (!empty($subtype)) $subtype = sanitise_string($subtype);
				if (!empty($action_type)) $action_type = sanitise_string($action_type);
				$limit = (int) $limit;
				$offset = (int) $offset;
				$posted_min = (int) $posted_min;
				$posted_max = (int) $posted_max;
				
			// Construct 'where' clauses for the river
				$where = array();
				$where[] = str_replace("and enabled='yes'",'',str_replace('owner_guid','subject_guid',get_access_sql_suffix()));
				
				if (empty($subject_relationship)) {
					if (!empty($subject_guid))
						if (!is_array($subject_guid)) {
							$where[] = " subject_guid = {$subject_guid} ";
						} else {
							$where[] = " subject_guid in (" . implode(',',$subject_guid) . ") ";
						}
				} else {
					if (!is_array($subject_guid))
						if ($entities = get_entities_from_relationship($subject_relationship,$subject_guid,false,'','',0,'',9999)) {
							$guids = array();
							foreach($entities as $entity) $guids[] = (int) $entity->guid;
							// $guids[] = $subject_guid;
							$where[] = " subject_guid in (" . implode(',',$guids) . ") "; 
						} else {
							return array();
						}
				}
				if (!empty($object_guid))
	                if (!is_array($object_guid)) {
						$where[] = " object_guid = {$object_guid} ";
					} else {
						$where[] = " object_guid in (" . implode(',',$object_guid) . ") ";
					}
				if (!empty($type)) $where[] = " type = '{$type}' ";
				if (!empty($subtype)) $where[] = " subtype = '{$subtype}' ";
				if (!empty($action_type)) $where[] = " action_type = '{$action_type}' ";
				if (!empty($posted_min)) $where[] = " posted > {$posted_min} ";
				if (!empty($posted_max)) $where[] = " posted < {$posted_max} ";
				
				$whereclause = implode(' and ', $where);
				
			// Construct main SQL
				$sql = "select id,type,subtype,action_type,access_id,view,subject_guid,object_guid,posted from {$CONFIG->dbprefix}river where {$whereclause} order by posted desc limit {$offset},{$limit}";
				
			// Get data
				return get_data($sql);
			
		}
		
	/**
	 * Returns a human-readable representation of a river item
	 *
	 * @see get_river_items
	 * 
	 * @param stdClass $item A river item object as returned from get_river_items
	 * @return string|false Depending on success
	 */
		function elgg_view_river_item($item) {
			if (isset($item->view)) {

				$object = get_entity($item->object_guid);
				if (!$object) {
					$body = elgg_view('river/item/noaccess');
				} else {
					if (elgg_view_exists($item->view)) {
						$body = elgg_view($item->view,array(
											'item' => $item
										 ));
					}
				}
				return elgg_view('river/item/wrapper',array(
									'item' => $item,
									'body' => $body
								 ));
				
			}
			return false;
		}
		
	/**
	 * Returns a human-readable version of the river.
	 *
	 * @param int|array $subject_guid Acting entity to restrict to. Default: all
	 * @param int|array $object_guid Entity being acted on to restrict to. Default: all
	 * @param string $subject_relationship If set to a relationship type, this will use $subject_guid as the starting point and set the subjects to be all users this entity has this relationship with (eg 'friend'). Default: blank
	 * @param string $type The type of entity to restrict to. Default: all
	 * @param string $subtype The subtype of entity to restrict to. Default: all
	 * @param string $action_type The type of river action to restrict to. Default: all
	 * @param int $limit The number of items to retrieve. Default: 20
	 * @param int $posted_min The minimum time period to look at. Default: none
	 * @param int $posted_max The maximum time period to look at. Default: none
	 * @return string Human-readable river.
	 */
		function elgg_view_river_items($subject_guid = 0,
							$object_guid = 0,
							$subject_relationship = '',
							$type = '',
							$subtype = '',
							$action_type = '',
							$limit = 20,
							$posted_min = 0,
							$posted_max = 0,
							$pagination = true) {
								
			// Get input from outside world and sanitise it
				$offset = (int) get_input('offset',0);
				
			// Get river items, if they exist
				if ($riveritems = get_river_items($subject_guid,$object_guid,$subject_relationship,$type,$subtype,$action_type,($limit + 1),$offset,$posted_min,$posted_max)) {

					return elgg_view('river/item/list',array(
											'limit' => $limit,
											'offset' => $offset,
											'items' => $riveritems,
											'pagination' => $pagination
										));
					
				}
				
			return '';
				
		}

?>