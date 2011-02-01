<?php
/**
 * Elgg sites
 * Functions to manage multiple or single sites in an Elgg install
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * ElggSite
 * Representation of a "site" in the system.
 * @package Elgg
 * @subpackage Core
 */
class ElggSite extends ElggEntity {
	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 */
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['type'] = "site";
		$this->attributes['name'] = "";
		$this->attributes['description'] = "";
		$this->attributes['url'] = "";
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new site object, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If a db row then will attempt to load the rest of the data.
	 * @throws Exception if there was a problem creating the site.
	 */
	function __construct($guid = null) {
		$this->initialise_attributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a site table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid));
				}
			}

			// Is $guid is an ElggSite? Use a copy constructor
			else if ($guid instanceof ElggSite) {
				elgg_deprecated_notice('This type of usage of the ElggSite constructor was deprecated. Please use the clone method.', 1.7);
				
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// Is this is an ElggEntity but not an ElggSite = ERROR!
			else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggSite'));
			}

			// See if this is a URL
			else if (strpos($guid, "http") !== false) {
				$guid = get_site_by_url($guid);
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// We assume if we have got this far, $guid is an int
			else if (is_numeric($guid)) {
				if (!$this->load($guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid));
				}
			}

			else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
	}

	/**
	 * Override the load function.
	 * This function will ensure that all data is loaded (were possible), so
	 * if only part of the ElggSite is loaded, it'll load the rest.
	 *
	 * @param int $guid
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type']!='site') {
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		}

		// Load missing data
		$row = get_site_entity_as_row($guid);
		if (($row) && (!$this->isFullyLoaded())) {
			// If $row isn't a cached copy then increment the counter
			$this->attributes['tables_loaded'] ++;
		}

		// Now put these into the attributes array as core values
		$objarray = (array) $row;
		foreach($objarray as $key => $value) {
			$this->attributes[$key] = $value;
		}

		return true;
	}

	/**
	 * Override the save function.
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		return create_site_entity($this->get('guid'), $this->get('name'), $this->get('description'), $this->get('url'));
	}

	/**
	 * Delete this site.
	 */
	public function delete() {
		global $CONFIG;
		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
		}

		return parent::delete();
	}

	/**
	 * Disable override to add safety rail.
	 *
	 * @param unknown_type $reason
	 */
	public function disable($reason = "") {
		global $CONFIG;

		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
		}

		return parent::disable($reason);
	}

	/**
	 * Return a list of users using this site.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return array of ElggUsers
	 */
	public function getMembers($limit = 10, $offset = 0) {
		get_site_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * Add a user to the site.
	 *
	 * @param int $user_guid
	 */
	public function addUser($user_guid) {
		return add_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Remove a site user.
	 *
	 * @param int $user_guid
	 */
	public function removeUser($user_guid) {
		return remove_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Get an array of member ElggObjects.
	 *
	 * @param string $subtype
	 * @param int $limit
	 * @param int $offset
	 */
	public function getObjects($subtype="", $limit = 10, $offset = 0) {
		get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add an object to the site.
	 *
	 * @param int $user_id
	 */
	public function addObject($object_guid) {
		return add_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Remove a site user.
	 *
	 * @param int $user_id
	 */
	public function removeObject($object_guid) {
		return remove_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * This function does not work and will be removed.
	 *
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 * @return unknown
	 */
	public function getCollections($subtype="", $limit = 10, $offset = 0) {
		get_site_collections($this->getGUID(), $subtype, $limit, $offset);
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'description',
			'url',
		));
	}
}

/**
 * Return the site specific details of a site by a row.
 *
 * @param int $guid
 */
function get_site_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}sites_entity where guid=$guid");
}

/**
 * Create or update the extras table for a given site.
 * Call create_entity first.
 *
 * @param int $guid
 * @param string $name
 * @param string $description
 * @param string $url
 */
function create_site_entity($guid, $name, $description, $url) {
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);
	$url = sanitise_string($url);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		if ($exists = get_data_row("SELECT guid from {$CONFIG->dbprefix}sites_entity where guid = {$guid}")) {
			$result = update_data("UPDATE {$CONFIG->dbprefix}sites_entity set name='$name', description='$description', url='$url' where guid=$guid");
			if ($result!=false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (trigger_elgg_event('update',$entity->type,$entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		} else {
			// Update failed, attempt an insert.
			$result = insert_data("INSERT into {$CONFIG->dbprefix}sites_entity (guid, name, description, url) values ($guid, '$name','$description','$url')");
			if ($result!==false) {
				$entity = get_entity($guid);
				if (trigger_elgg_event('create',$entity->type,$entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		}
	}

	return false;
}

/**
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a site's extra data.
 * @todo remove
 * @param int $guid
 */
function delete_site_entity($guid) {
	system_message(sprintf(elgg_echo('deprecatedfunction'), 'delete_user_entity'));

	return 1; // Always return that we have deleted one row in order to not break existing code.
}

/**
 * Add a user to a site.
 *
 * @param int $site_guid
 * @param int $user_guid
 */
function add_site_user($site_guid, $user_guid) {
	global $CONFIG;

	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	return add_entity_relationship($user_guid, "member_of_site", $site_guid);
}

/**
 * Remove a user from a site.
 *
 * @param int $site_guid
 * @param int $user_guid
 */
function remove_site_user($site_guid, $user_guid) {
	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	return remove_entity_relationship($user_guid, "member_of_site", $site_guid);
}

/**
 * Get the members of a site.
 *
 * @param int $site_guid
 * @param int $limit
 * @param int $offset
 */
function get_site_members($site_guid, $limit = 10, $offset = 0) {
	$site_guid = (int)$site_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site', 
		'relationship_guid' => $site_guid, 
		'inverse_relationship' => TRUE, 
		'types' => 'user', 
		'limit' => $limit, 'offset' => $offset
	));
}

/**
 * Display a list of site members
 *
 * @param int $site_guid The GUID of the site
 * @param int $limit The number of members to display on a page
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @return string A displayable list of members
 */
function list_site_members($site_guid, $limit = 10, $fullview = true) {
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$options = array(
		'relationship' => 'member_of_site', 
		'relationship_guid' => $site_guid, 
		'inverse_relationship' => TRUE, 
		'types' => 'user',
		'limit' => $limit, 
		'offset' => $offset, 
		'count' => TRUE
	);
	$count = (int) elgg_get_entities_from_relationship($options);
	$entities = get_site_members($site_guid, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview);

}

/**
 * Add an object to a site.
 *
 * @param int $site_guid
 * @param int $object_guid
 */
function add_site_object($site_guid, $object_guid) {
	global $CONFIG;

	$site_guid = (int)$site_guid;
	$object_guid = (int)$object_guid;

	return add_entity_relationship($object_guid, "member_of_site", $site_guid);
}

/**
 * Remove an object from a site.
 *
 * @param int $site_guid
 * @param int $object_guid
 */
function remove_site_object($site_guid, $object_guid) {
	$site_guid = (int)$site_guid;
	$object_guid = (int)$object_guid;

	return remove_entity_relationship($object_guid, "member_of_site", $site_guid);
}

/**
 * Get the objects belonging to a site.
 *
 * @param int $site_guid
 * @param string $subtype
 * @param int $limit
 * @param int $offset
 */
function get_site_objects($site_guid, $subtype = "", $limit = 10, $offset = 0) {
	$site_guid = (int)$site_guid;
	$subtype = sanitise_string($subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $site_guid, 
		'inverse_relationship' => TRUE, 
		'types' => 'object', 
		'subtypes' => $subtype, 
		'limit' => $limit, 
		'offset' => $offset
	));
}

/**
 * The collection functionality does not work and will be removed.
 *
 * @param int $site_guid
 * @param int $collection_guid
 */
function add_site_collection($site_guid, $collection_guid) {
	global $CONFIG;

	$site_guid = (int)$site_guid;
	$collection_guid = (int)$collection_guid;

	return add_entity_relationship($collection_guid, "member_of_site", $site_guid);
}

/**
 * The collection functionality does not work and will be removed.
 *
 * @param int $site_guid
 * @param int $collection_guid
 */
function remove_site_collection($site_guid, $collection_guid) {
	$site_guid = (int)$site_guid;
	$collection_guid = (int)$collection_guid;

	return remove_entity_relationship($collection_guid, "member_of_site", $site_guid);
}

/**
 * The collection functionality does not work and will be removed.
 *
 * @param int $site_guid
 * @param string $subtype
 * @param int $limit
 * @param int $offset
 */
function get_site_collections($site_guid, $subtype = "", $limit = 10, $offset = 0) {
	$site_guid = (int)$site_guid;
	$subtype = sanitise_string($subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;

	// collection isn't a valid type.  This won't work.
	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site', 
		'relationship_guid' => $site_guid, 
		'inverse_relationship' => TRUE, 
		'types' => 'collection', 
		'subtypes' => $subtype, 
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Return the site via a url.
 */
function get_site_by_url($url) {
	global $CONFIG;

	$url = sanitise_string($url);

	$row = get_data_row("SELECT * from {$CONFIG->dbprefix}sites_entity where url='$url'");

	if ($row) {
		return new ElggSite($row);
	}

	return false;
}

/**
 * Searches for a site based on a complete or partial name or description or url using full text searching.
 *
 * IMPORTANT NOTE: With MySQL's default setup:
 * 1) $criteria must be 4 or more characters long
 * 2) If $criteria matches greater than 50% of results NO RESULTS ARE RETURNED!
 *
 * @param string $criteria The partial or full name or username.
 * @param int $limit Limit of the search.
 * @param int $offset Offset.
 * @param string $order_by The order.
 * @param boolean $count Whether to return the count of results or just the results.
 * @deprecated 1.7
 */
function search_for_site($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_site() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}sites_entity s on e.guid=s.guid where match(s.name,s.description,s.url) against ('$criteria') and $access";

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Retrieve a site and return the domain portion of its url.
 *
 * @param int $guid
 */
function get_site_domain($guid) {
	$guid = (int)$guid;

	$site = get_entity($guid);
	if ($site instanceof ElggSite) {
		$breakdown = parse_url($site->url);
		return $breakdown['host'];
	}

	return false;
}

/**
 * Initialise site handling
 *
 * Called at the beginning of system running, to set the ID of the current site.
 * This is 0 by default, but plugins may alter this behaviour by attaching functions
 * to the sites init event and changing $CONFIG->site_id.
 *
 * @uses $CONFIG
 * @param string $event Event API required parameter
 * @param string $object_type Event API required parameter
 * @param null $object Event API required parameter
 * @return true
 */
function sites_init($event, $object_type, $object) {
	global $CONFIG;

	if (is_installed() && is_db_installed()) {
		$site = trigger_plugin_hook("siteid","system");
		if ($site === null || $site === false) {
			$CONFIG->site_id = (int) datalist_get('default_site');
		} else {
			$CONFIG->site_id = $site;
		}
		$CONFIG->site_guid = $CONFIG->site_id;
		$CONFIG->site = get_entity($CONFIG->site_guid);

		return true;
	}

	return true;
}

// Register event handlers
register_elgg_event_handler('boot','system','sites_init',2);

// Register with unit test
register_plugin_hook('unit_test', 'system', 'sites_test');
function sites_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/sites.php";
	return $value;
}
