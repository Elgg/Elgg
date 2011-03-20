<?php
/**
 * Elgg sites
 * Functions to manage multiple or single sites in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.Site
 */

/**
 * Get an ElggSite entity (default is current site)
 *
 * @param int $site_guid Optional. Site GUID.
 *
 * @return ElggSite
 * @since 1.8.0
 */
function elgg_get_site_entity($site_guid = 0) {
	global $CONFIG;

	if ($site_guid == 0) {
		return $CONFIG->site;
	}

	return get_entity($site_guid);
}

/**
 * Return the site specific details of a site by a row.
 *
 * @param int $guid The site GUID
 *
 * @return mixed
 */
function get_site_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * from {$CONFIG->dbprefix}sites_entity where guid=$guid");
}

/**
 * Create or update the entities table for a given site.
 * Call create_entity first.
 *
 * @param int    $guid        Site GUID
 * @param string $name        Site name
 * @param string $description Site Description
 * @param string $url         URL of the site
 *
 * @return bool
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
		$query = "SELECT guid from {$CONFIG->dbprefix}sites_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}sites_entity
				set name='$name', description='$description', url='$url' where guid=$guid";
			$result = update_data($query);

			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}sites_entity
				(guid, name, description, url) values ($guid, '$name', '$description', '$url')";
			$result = insert_data($query);

			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
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
 * Add a user to a site.
 *
 * @param int $site_guid Site guid
 * @param int $user_guid User guid
 *
 * @return bool
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
 * @param int $site_guid Site GUID
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function remove_site_user($site_guid, $user_guid) {
	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	return remove_entity_relationship($user_guid, "member_of_site", $site_guid);
}

/**
 * Add an object to a site.
 *
 * @param int $site_guid   Site GUID
 * @param int $object_guid Object GUID
 *
 * @return mixed
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
 * @param int $site_guid   Site GUID
 * @param int $object_guid Object GUID
 *
 * @return bool
 */
function remove_site_object($site_guid, $object_guid) {
	$site_guid = (int)$site_guid;
	$object_guid = (int)$object_guid;

	return remove_entity_relationship($object_guid, "member_of_site", $site_guid);
}

/**
 * Get the objects belonging to a site.
 *
 * @param int    $site_guid Site GUID
 * @param string $subtype   Subtype
 * @param int    $limit     Limit
 * @param int    $offset    Offset
 *
 * @return mixed
 */
function get_site_objects($site_guid, $subtype = "", $limit = 10, $offset = 0) {
	$site_guid = (int)$site_guid;
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
 * Return the site via a url.
 *
 * @param string $url The URL of a site
 *
 * @return mixed
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
 * Retrieve a site and return the domain portion of its url.
 *
 * @param int $guid ElggSite GUID
 *
 * @return string
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
 *
 * @param string $event       Event API required parameter
 * @param string $object_type Event API required parameter
 * @param null   $object      Event API required parameter
 *
 * @return true
 */
function sites_boot($event, $object_type, $object) {
	global $CONFIG;

	$site = elgg_trigger_plugin_hook("siteid", "system");
	if ($site === null || $site === false) {
		$CONFIG->site_id = (int) datalist_get('default_site');
	} else {
		$CONFIG->site_id = $site;
	}
	$CONFIG->site_guid = $CONFIG->site_id;
	$CONFIG->site = get_entity($CONFIG->site_guid);

	return true;
}

// Register event handlers
elgg_register_event_handler('boot', 'system', 'sites_boot', 2);

// Register with unit test
elgg_register_plugin_hook_handler('unit_test', 'system', 'sites_test');

/**
 * Unit tests for sites
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function sites_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/sites.php";
	return $value;
}
