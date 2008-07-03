<?php
	/**
	 * Elgg Groups.
	 * Groups contain other entities, or rather act as a placeholder for other entities to mark any given container
	 * as their container.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * @class ElggGroup Class representing a container for other elgg entities.
	 * @author Marcus Povey
	 */
	class ElggGroup extends ElggEntity
	{
		protected function initialise_attributes()
		{
			parent::initialise_attributes();
			
			$this->attributes['type'] = "group";
			$this->attributes['name'] = "";
			$this->attributes['description'] = "";
			$this->attributes['tables_split'] = 2;
		}
		
		/**
		 * Construct a new user entity, optionally from a given id value.
		 *
		 * @param mixed $guid If an int, load that GUID. 
		 * 	If a db row then will attempt to load the rest of the data.
		 * @throws Exception if there was a problem creating the user. 
		 */
		function __construct($guid = null) 
		{			
			$this->initialise_attributes();
			
			if (!empty($guid))
			{
				// Is $guid is a DB row - either a entity row, or a user table row.
				if ($guid instanceof stdClass) {					
					// Load the rest
					if (!$this->load($guid->guid))
						throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid)); 
				}
						
				// Is $guid is an ElggGroup? Use a copy constructor
				else if ($guid instanceof ElggGroup)
				{					
					 foreach ($guid->attributes as $key => $value)
					 	$this->attributes[$key] = $value;
				}
				
				// Is this is an ElggEntity but not an ElggGroup = ERROR!
				else if ($guid instanceof ElggEntity)
					throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggGroup'));
										
				// We assume if we have got this far, $guid is an int
				else if (is_numeric($guid)) {					
					if (!$this->load($guid)) IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid));
				}
				
				else
					throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
		
		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		function __get($name) { return $this->get($name); }
		
		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return mixed
		 */
		function __set($name, $value) { return $this->set($name, $value); }
		
		/**
		 * Add an ElggObject to this group.
		 *
		 * @param ElggObject $object The object.
		 * @return bool
		 */
		public function addObjectToGroup(ElggObject $object)
		{
			return add_object_to_group($this->getGUID(), $object->getGUID());
		}
		
		/**
		 * Remove an object from the containing group.
		 *
		 * @param int $guid The guid of the object.
		 * @return bool
		 */
		public function removeObjectFromGroup($guid)
		{
			return remove_object_from_group($this->getGUID(), $guid);
		}
		
		/**
		 * Returns whether the given user (or current user) has the ability to write to this group.
		 *
		 * @param int $user_guid The user.
		 * @return bool
		 */
		public function can_write_to_container($user_guid = 0)
		{
			return can_write_to_container($user_guid, $this->getGUID());
		}
		
		/**
		 * Get objects contained in this group.
		 *
		 * @param int $limit
		 * @param int $offset
		 * @param string $subtype
		 * @param int $owner_guid
		 * @param int $site_guid
		 * @param string $order_by
		 * @return mixed
		 */
		public function getObjects($limit = 10, $offset = 0, $subtype = "", $owner_guid = 0, $site_guid = 0, $order_by = "") 
		{
			return get_objects_in_group($this->getGUID(), $subtype, $owner_guid, $site_guid, $order_by, $limit, $offset, false);
		}
		
		/**
		 * Get a list of group members.
		 *
		 * @param int $limit
		 * @param int $offset
		 * @return mixed
		 */
		public function getMembers($limit = 10, $offset = 0)
		{
			return get_group_members($this->getGUID(), $limit, $offset);
		}
		
		/**
		 * Return whether a given user is a member of this group or not.
		 *
		 * @param ElggUser $user The user
		 * @return bool
		 */
		public function isMember(ElggUser $user)
		{
			return is_group_member($this->getGUID(), $user->getGUID());
		}
		
		/**
		 * Join an elgg user to this group.
		 *
		 * @param ElggUser $user
		 * @return bool
		 */
		public function join(ElggUser $user)
		{
			return join_group($this->getGUID(), $user->getGUID());
		}
		
		/**
		 * Remove a user from the group.
		 *
		 * @param ElggUser $user
		 */
		public function leave(ElggUser $user)
		{
			return leave_group($this->getGUID(), $user->getGUID());
		}
		
		/**
		 * Delete this group.
		 */
		public function delete() 
		{ 
			if (!parent::delete())
				return false;
				
			return delete_group_entity($this->get('guid'));
		}
		
		
		/**
		 * Override the load function.
		 * This function will ensure that all data is loaded (were possible), so
		 * if only part of the ElggGroup is loaded, it'll load the rest.
		 * 
		 * @param int $guid 
		 */
		protected function load($guid)
		{			
			// Test to see if we have the generic stuff
			if (!parent::load($guid)) 
				return false;

			// Check the type
			if ($this->attributes['type']!='group')
				throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
				
			// Load missing data
			$row = get_group_entity_as_row($guid);
			if (($row) && (!$this->isFullyLoaded())) $this->attributes['tables_loaded'] ++;	// If $row isn't a cached copy then increment the counter
			
			// Now put these into the attributes array as core values
			$objarray = (array) $row;
			foreach($objarray as $key => $value) 
				$this->attributes[$key] = $value;
			
			return true;
		}
		
		/**
		 * Override the save function.
		 */
		public function save()
		{
			// Save generic stuff
			if (!parent::save())
				return false;
		
			// Now save specific stuff
			return create_group_entity($this->get('guid'), $this->get('name'), $this->get('description'));
		}
	}

	/**
	 * Determine whether a given user is able to write to a given group.
	 *
	 * @param int $user_guid The user guid, or 0 for $_SESSION['user']->getGUID()
	 * @param int $container_guid The container, or 0 for the current page owner.
	 */
	function can_write_to_container($user_guid = 0, $container_guid = 0)
	{
		global $CONFIG;
		
		$user_guid = (int)$user_guid;
		if (!$user_guid) $user_guid = $_SESSION['user']->getGUID();
		$user = get_entity($user_guid);
		
		$container_guid = (int)$container_guid;
		if (!$container_guid) $container_guid = page_owner();
		$container = get_entity($container_guid);

		if (($container) && ($user))
		{
			// Basics, see if the user is a member of the group.
			if (!$container->isMember($user)) return false;
			
			// See if anyone else has anything to say
			return trigger_plugin_hook('group_permissions_check',$entity->type,array('container' => $container, 'user' => $user), false);
			
		}
		
		return false;
	}
	
	/**
	 * Get the group entity.
	 *
	 * @param int $guid
	 */
	function get_group_entity_as_row($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$row = retrieve_cached_entity_row($guid);
		if ($row)
		{
			// We have already cached this object, so retrieve its value from the cache
			if (isset($CONFIG->debug) && $CONFIG->debug)
				error_log("** Retrieving sub part of GUID:$guid from cache");
				
			return $row;
		}
		else
		{
			// Object not cached, load it.
			if (isset($CONFIG->debug) && $CONFIG->debug == true)
				error_log("** Sub part of GUID:$guid loaded from DB");
		
			return get_data_row("SELECT * from {$CONFIG->dbprefix}groups_entity where guid=$guid");
		}
	}

	/**
	 * Create or update the extras table for a given group.
	 * Call create_entity first.
	 * 
	 * @param int $guid
	 * @param string $name
	 * @param string $description
	 */
	function create_group_entity($guid, $name, $description)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		$name = sanitise_string($name);
		$description = sanitise_string($description);
		
		$row = get_entity_as_row($guid);
		
		if ($row)
		{
			// Exists and you have access to it
			if ($exists = get_data_row("select guid from {$CONFIG->dbprefix}groups_entity where guid = {$guid}")) {
				$result = update_data("UPDATE {$CONFIG->dbprefix}groups_entity set name='$name', description='$description' where guid=$guid");
				if ($result!=false)
				{
					// Update succeeded, continue
					$entity = get_entity($guid);
					if (trigger_elgg_event('update',$entity->type,$entity)) {
						return true;
					} else {
						delete_entity($guid);
					}
				}
			}
			else
			{
				// Update failed, attempt an insert.
				$result = insert_data("INSERT into {$CONFIG->dbprefix}groups_entity (guid, name, description) values ($guid, '$name','$description')");
				if ($result!==false) {
					$entity = get_entity($guid);
					if (trigger_elgg_event('create',$entity->type,$entity)) {
						return true;
					} else {
						delete_entity($guid);
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Delete a group's extra data.
	 *
	 * @param int $guid The guid of the group
	 * @return bool
	 */
	function delete_group_entity($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$row = get_entity_as_row($guid);
		
		// Check to see if we have access and it exists
		if ($row) 
		{
			// Delete any existing stuff
			return delete_data("DELETE from {$CONFIG->dbprefix}groups_entity where guid=$guid");
		}
		
		return false;
	}
	
	/**
	 * Add an object to the given group.
	 *
	 * @param int $group_guid The group to add the object to.
	 * @param int $object_guid The guid of the elgg object (must be ElggObject or a child thereof)
	 * @return bool
	 */
	function add_object_to_group($group_guid, $object_guid)
	{
		$group_guid = (int)$group_guid;
		$object_guid = (int)$object_guid;
		
		$group = get_entity($group_guid);
		$object = get_entity($object_guid);
		
		if ((!$group) || (!$object)) return false;
		
		if (!($group instanceof ElggGroup))
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $group_guid, 'ElggGroup'));

		if (!($object instanceof ElggObject))
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $object_guid, 'ElggObject'));

		$object->container_guid = $group_guid;
		return $object->save();
	}
	
	/**
	 * Remove an object from the given group.
	 *
	 * @param int $group_guid The group to remove the object from
	 * @param int $object_guid The object to remove
	 */
	function remove_object_from_group($group_guid, $object_guid)
	{
		$group_guid = (int)$group_guid;
		$object_guid = (int)$object_guid;
		
		$group = get_entity($group_guid);
		$object = get_entity($object_guid);
		
		if ((!$group) || (!$object)) return false;
		
		if (!($group instanceof ElggGroup))
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $group_guid, 'ElggGroup'));

		if (!($object instanceof ElggObject))
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $object_guid, 'ElggObject'));

		$object->container_guid = $object->owner_guid;
		return $object->save();
	}
	
	/**
	 * Return an array of objects in a given container.
	 * @see get_entities()
	 *
	 * @param int $group_guid The container (defaults to current page owner)
	 * @param string $subtype The subtype
	 * @param int $owner_guid Owner
	 * @param int $site_guid The site
	 * @param string $order_by Order
	 * @param unknown_type $limit Limit on number of elements to return, by default 10.
	 * @param unknown_type $offset Where to start, by default 0.
	 * @param unknown_type $count Whether to return the entities or a count of them.
	 */
	function get_objects_in_group($group_id, $subtype = "", $owner_guid = 0, $site_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false)
	{
		global $CONFIG;
		
		if ($subtype === false || $subtype === null || $subtype === 0)
			return false;
			
		$subtype = get_subtype_id('object', $subtype);
		
		if ($order_by == "") $order_by = "e.time_created desc";
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
		
		$container_guid = (int)$group_guid;
		if ($container_guid == 0)
			$container_guid = page_owner();
				
		$where = array();
		
		$where[] = "e.type='object'";
		if ($subtype!=="")
			$where[] = "e.subtype=$subtype";
		if ($owner_guid != "") {
			if (!is_array($owner_guid)) {
				$owner_guid = (int) $owner_guid;
				$where[] = "e.owner_guid = '$owner_guid'";
			} else if (sizeof($owner_guid) > 0) {
				// Cast every element to the owner_guid array to int
				$owner_guid = array_map("sanitise_int", $owner_guid);
				$owner_guid = implode(",",$owner_guid);
				$where[] = "e.owner_guid in ({$owner_guid})";
			}
		}
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";

		if ($container_guid > 0)
			$where[] = "o.container_guid = {$container_guid}";
			
		if (!$count) {
			$query = "SELECT * from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
		} else {
			$query = "select count(e.guid) as total from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
		}
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix('e'); // Add access controls
		if (!$count) {
			$query .= " order by $order_by";
			if ($limit) $query .= " limit $offset, $limit"; // Add order and limit

			$dt = get_data($query, "entity_row_to_elggstar");
			return $dt;
		} else {
			$total = get_data_row($query);
			return $total->total;
		}
	}
	
	/**
	 * Return a list of this group's members.
	 * 
	 * @param int $group_guid The ID of the container/group.
	 * @param int $limit The limit
	 * @param int $offset The offset
	 * @param int $site_guid The site
	 * @param bool $count Return the users (false) or the count of them (true)
	 * @return mixed
	 */
	function get_group_members($group_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false)
	{
		return get_entities_from_relationship('member', $group_guid, true, 'user', '', 0, "", $limit, $offset, $count, $site_guid);
	}
	
	/**
	 * Return whether a given user is a member of the group or not.
	 * 
	 * @param int $group_guid The group ID
	 * @param int $user_guid The user guid
	 * @return bool
	 */
	function is_group_member($group_guid, $user_guid)
	{
		return check_entity_relationship($user_guid, 'member', $group_guid);
	}
	
	/**
	 * Join a user to a group.
	 * 
	 * @param int $group_guid The group.
	 * @param int $user_guid The user.
	 */
	function join_group($group_guid, $user_guid)
	{
		return add_entity_relationship($user_guid, 'member', $group_guid);
	}
	
	/**
	 * Remove a user from a group.
	 * 
	 * @param int $group_guid The group.
	 * @param int $user_guid The user.
	 */
	function leave_group($group_guid, $user_guid)
	{
		return remove_entity_relationship($user_guid, 'member', $group_guid);
	}
?>