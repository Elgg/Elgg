<?php

/**
 * Elgg objects
 * Functions to manage multiple or single objects in an Elgg install
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * ElggObject
 * Representation of an "object" in the system.
 *
 * @package Elgg
 * @subpackage Core
 */
class ElggObject extends ElggEntity {
	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 */
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['type'] = "object";
		$this->attributes['title'] = "";
		$this->attributes['description'] = "";
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new object entity, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If a db row then will attempt to load the rest of the data.
	 * @throws Exception if there was a problem creating the object.
	 */
	function __construct($guid = null) {
		$this->initialise_attributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a object table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid));
				}
			}

			// Is $guid is an ElggObject? Use a copy constructor
			else if ($guid instanceof ElggObject) {
				elgg_deprecated_notice('This type of usage of the ElggObject constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// Is this is an ElggEntity but not an ElggObject = ERROR!
			else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggObject'));
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
	 * if only part of the ElggObject is loaded, it'll load the rest.
	 *
	 * @param int $guid
	 * @return true|false
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type']!='object') {
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		}

		// Load missing data
		$row = get_object_entity_as_row($guid);
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
	 * @return true|false
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		return create_object_entity($this->get('guid'), $this->get('title'), $this->get('description'), $this->get('container_guid'));
	}

	/**
	 * Get sites that this object is a member of
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int $limit The number of results to return
	 * @param int $offset Any indexing offset
	 */
	function getSites($subtype="", $limit = 10, $offset = 0) {
		return get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add this object to a particular site
	 *
	 * @param int $site_guid The guid of the site to add it to
	 * @return true|false
	 */
	function addToSite($site_guid) {
		return add_site_object($this->getGUID(), $site_guid);
	}

	/**
	 * Set the container for this object.
	 *
	 * @param int $container_guid The ID of the container.
	 * @return bool
	 */
	function setContainer($container_guid) {
		$container_guid = (int)$container_guid;

		return $this->set('container_guid', $container_guid);
	}

	/**
	 * Return the container GUID of this object.
	 *
	 * @return int
	 */
	function getContainer() {
		return $this->get('container_guid');
	}

	/**
	 * As getContainer(), but returns the whole entity.
	 *
	 * @return mixed ElggGroup object or false.
	 */
	function getContainerEntity() {
		$result = get_entity($this->getContainer());

		if (($result) && ($result instanceof ElggGroup)) {
			return $result;
		}

		return false;
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'title',
			'description',
		));
	}
}

/**
 * Return the object specific details of a object by a row.
 *
 * @param int $guid
 */
function get_object_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}objects_entity where guid=$guid");
}

/**
 * Create or update the extras table for a given object.
 * Call create_entity first.
 *
 * @param int $guid The guid of the entity you're creating (as obtained by create_entity)
 * @param string $title The title of the object
 * @param string $description The object's description
 */
function create_object_entity($guid, $title, $description) {
	global $CONFIG;

	$guid = (int)$guid;
	$title = sanitise_string($title);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Core entities row exists and we have access to it
		if ($exists = get_data_row("SELECT guid from {$CONFIG->dbprefix}objects_entity where guid = {$guid}")) {
			$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity set title='$title', description='$description' where guid=$guid");
			if ($result!=false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (trigger_elgg_event('update',$entity->type,$entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		} else {
			// Update failed, attempt an insert.
			$result = insert_data("INSERT into {$CONFIG->dbprefix}objects_entity (guid, title, description) values ($guid, '$title','$description')");
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
 * Delete a object's extra data.
 * @todo - this should be removed - was deprecated in 1.5 or earlier
 * @param int $guid
 */
function delete_object_entity($guid) {
	system_message(sprintf(elgg_echo('deprecatedfunction'), 'delete_user_entity'));

	return 1; // Always return that we have deleted one row in order to not break existing code.
}

/**
 * Searches for an object based on a complete or partial title or description using full text searching.
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
function search_for_object($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_object() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);
	$container_guid = (int)$container_guid;

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where match(o.title,o.description) against ('$criteria') and $access";

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
 * Get the sites this object is part of
 *
 * @param int $object_guid The object's GUID
 * @param int $limit Number of results to return
 * @param int $offset Any indexing offset
 * @return false|array On success, an array of ElggSites
 */
function get_object_sites($object_guid, $limit = 10, $offset = 0) {
	$object_guid = (int)$object_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $object_guid,
		'types' => 'site',
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Runs unit tests for ElggObject
 */
function objects_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/objects.php";
	return $value;
}


/**
 * Returns a formatted list of objects suitable for injecting into search.
 * @deprecated 1.7
 *
 */
function search_list_objects_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_objects_by_name was deprecated by new search plugin.', 1.7);

	// Change this to set the number of users that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'user')) {
		if ($users = search_for_user($tag,$threshold)) {
			$countusers = search_for_user($tag,0,0,"",true);

			$return = elgg_view('user/search/startblurb',array('count' => $countusers, 'tag' => $tag));
			foreach($users as $user) {
				$return .= elgg_view_entity($user);
			}
			$return .= elgg_view('user/search/finishblurb',array('count' => $countusers, 'threshold' => $threshold, 'tag' => $tag));
			return $return;

		}
	}
}

register_elgg_event_handler('init', 'system', 'objects_init', 0);
register_plugin_hook('unit_test', 'system', 'objects_test');
