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

	$result = false;
	
	if ($site_guid == 0) {
		$site = $CONFIG->site;
	} else {
		$site = get_entity($site_guid);
	}
	
	if ($site instanceof ElggSite) {
		$result = $site;
	}

	return $result;
}

/**
 * Return the site specific details of a site by a row.
 *
 * @param int $guid The site GUID
 *
 * @return mixed
 * @access private
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
 * @access private
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
		'type' => 'object',
		'subtype' => $subtype,
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
		return get_entity($row->guid);
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
 * Unit tests for sites
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function sites_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/sites.php";
	return $value;
}

// Register with unit test
elgg_register_plugin_hook_handler('unit_test', 'system', 'sites_test');
