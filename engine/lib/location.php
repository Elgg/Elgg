<?php
/**
 * Elgg geo-location tagging library.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Define an interface for geo-tagging entities.
 *
 */
interface Locatable {
	/** Set a location text */
	public function setLocation($location);

	/**
	 * Set latitude and longitude tags for a given entity.
	 *
	 * @param float $lat
	 * @param float $long
	 */
	public function setLatLong($lat, $long);

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 */
	public function getLatitude();

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 */
	public function getLongitude();

	/**
	 * Get the ->location metadata.
	 *
	 */
	public function getLocation();
}

/**
 * Encode a location into a latitude and longitude, caching the result.
 *
 * Works by triggering the 'geocode' 'location' plugin hook, and requires a geocoding module to be installed
 * activated in order to work.
 *
 * @param String $location The location, e.g. "London", or "24 Foobar Street, Gotham City"
 */
function elgg_geocode_location($location) {
	global $CONFIG;

	// Handle cases where we are passed an array (shouldn't be but can happen if location is a tag field)
	if (is_array($location)) {
		$location = implode(', ', $location);
	}

	$location = sanitise_string($location);

	// Look for cached version
	$cached_location = get_data_row("SELECT * from {$CONFIG->dbprefix}geocode_cache WHERE location='$location'");

	if ($cached_location) {
		return array('lat' => $cached_location->lat, 'long' => $cached_location->long);
	}

	// Trigger geocode event if not cached
	$return = false;
	$return = trigger_plugin_hook('geocode', 'location', array('location' => $location), $return);

	// If returned, cache and return value
	if (($return) && (is_array($return))) {
		$lat = (float)$return['lat'];
		$long = (float)$return['long'];

		// Put into cache at the end of the page since we don't really care that much
		execute_delayed_write_query("INSERT DELAYED INTO {$CONFIG->dbprefix}geocode_cache (location, lat, `long`) VALUES ('$location', '{$lat}', '{$long}') ON DUPLICATE KEY UPDATE lat='{$lat}', `long`='{$long}'");
	}

	return $return;
}

/**
 * Return entities within a given geographic area.
 *
 * @param real $lat Latitude
 * @param real $long Longitude
 * @param real $radius The radius
 * @param string $type The type of entity (eg "user", "object" etc)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param string $order_by The field to order by; by default, time_created desc
 * @param int $limit The number of entities to return; 10 by default
 * @param int $offset The indexing offset, 0 by default
 * @param boolean $count Set to true to get a count rather than the entities themselves (limits and offsets don't apply in this context). Defaults to false.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param int|array $container_guid The container or containers to get entities from (default: all containers).
 * @return array A list of entities.
 */
function get_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid) {
	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	$lat = (real)$lat;
	$long = (real)$long;
	$radius = (real)$radius;

	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$where = array();

	if (is_array($type)) {
		$tempwhere = "";
		if (sizeof($type)) {
			foreach($type as $typekey => $subtypearray) {
				foreach($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						$subtypeval = (int) get_subtype_id($typekey, $subtypeval);
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) $tempwhere .= " or ";
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);

		if ($type != "") {
			$where[] = "e.type='$type'";
		}

		if ($subtype!=="") {
			$where[] = "e.subtype=$subtype";
		}
	}

	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_array = array($owner_guid);
			$owner_guid = (int) $owner_guid;
			$where[] = "e.owner_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
			// Cast every element to the owner_guid array to int
			$owner_guid = implode(",",$owner_guid); //
			$where[] = "e.owner_guid in ({$owner_guid})" ; //
		}
		if (is_null($container_guid)) {
			$container_guid = $owner_array;
		}
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (!is_null($container_guid)) {
		if (is_array($container_guid)) {
			foreach($container_guid as $key => $val) $container_guid[$key] = (int) $val;
			$where[] = "e.container_guid in (" . implode(",",$container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "e.container_guid = {$container_guid}";
		}
	}

	// Add the calendar stuff
	$loc_join = "
		JOIN {$CONFIG->dbprefix}metadata loc_start on e.guid=loc_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings loc_start_name on loc_start.name_id=loc_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings loc_start_value on loc_start.value_id=loc_start_value.id

		JOIN {$CONFIG->dbprefix}metadata loc_end on e.guid=loc_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings loc_end_name on loc_end.name_id=loc_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings loc_end_value on loc_end.value_id=loc_end_value.id
	";

	$lat_min = $lat - $radius;
	$lat_max = $lat + $radius;
	$long_min = $long - $radius;
	$long_max = $long + $radius;

	$where[] = "loc_start_name.string='geo:lat'";
	$where[] = "loc_start_value.string>=$lat_min";
	$where[] = "loc_start_value.string<=$lat_max";
	$where[] = "loc_end_name.string='geo:long'";
	$where[] = "loc_end_value.string >= $long_min";
	$where[] = "loc_end_value.string <= $long_max";

	if (!$count) {
		$query = "SELECT e.* from {$CONFIG->dbprefix}entities e $loc_join where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e $loc_join where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	$query .= get_access_sql_suffix('e'); // Add access controls

	if (!$count) {
		$query .= " order by n.calendar_start $order_by";
		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}
		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * List entities in a given location
 *
 * @param string $location Location
 * @param string $type The type of entity (eg "user", "object" etc)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param int $limit The number of entities to display per page (default: 10)
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @param true|false $viewtypetoggle Whether or not to allow gallery view
 * @param true|false $pagination Display pagination? Default: true
 * @return string A viewable list of entities
 */
function list_entities_location($location, $type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $navigation = true) {
	return list_entities_from_metadata('location', $location, $type, $subtype, $owner_guid, $limit, $fullview, $viewtypetoggle, $navigation);
}

/**
 * List items within a given geographic area.
 *
 * @param real $lat Latitude
 * @param real $long Longitude
 * @param real $radius The radius
 * @param string $type The type of entity (eg "user", "object" etc)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param int $limit The number of entities to display per page (default: 10)
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @param true|false $viewtypetoggle Whether or not to allow gallery view
 * @param true|false $pagination Display pagination? Default: true
 * @return string A viewable list of entities
 */
function list_entities_in_area($lat, $long, $radius, $type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $navigation = true) {

	$offset = (int) get_input('offset');
	$count = get_entities_in_area($lat, $long, $radius, $type, $subtype, $owner_guid, "", $limit, $offset, true);
	$entities = get_entities_in_area($lat, $long, $radius, $type, $subtype, $owner_guid, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $navigation);
}

// Some distances in degrees (approximate)
define("MILE", 0.01515);
define("KILOMETER", 0.00932);

// @todo get objects within x miles by entities, metadata and relationship

// @todo List