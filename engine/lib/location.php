<?php
/**
 * Elgg geo-location tagging library.
 *
 * @package Elgg.Core
 * @subpackage Location
 */

/**
 * Encode a location into a latitude and longitude, caching the result.
 *
 * Works by triggering the 'geocode' 'location' plugin
 * hook, and requires a geocoding plugin to be installed.
 *
 * @param string $location The location, e.g. "London", or "24 Foobar Street, Gotham City"
 * @return string|false
 */
function elgg_geocode_location($location) {
	global $CONFIG;

	if (is_array($location)) {
		return false;
	}

	$location = sanitise_string($location);

	// Look for cached version
	$query = "SELECT * from {$CONFIG->dbprefix}geocode_cache WHERE location='$location'";
	$cached_location = get_data_row($query);

	if ($cached_location) {
		return array('lat' => $cached_location->lat, 'long' => $cached_location->long);
	}

	// Trigger geocode event if not cached
	$return = false;
	$return = elgg_trigger_plugin_hook('geocode', 'location', array('location' => $location), $return);

	// If returned, cache and return value
	if (($return) && (is_array($return))) {
		$lat = (float)$return['lat'];
		$long = (float)$return['long'];

		// Put into cache at the end of the page since we don't really care that much
		$query = "INSERT DELAYED INTO {$CONFIG->dbprefix}geocode_cache "
			. " (location, lat, `long`) VALUES ('$location', '{$lat}', '{$long}')"
			. " ON DUPLICATE KEY UPDATE lat='{$lat}', `long`='{$long}'";
		execute_delayed_write_query($query);
	}

	return $return;
}

/**
 * Return entities within a given geographic area.
 *
 * @param float     $lat            Latitude
 * @param float     $long           Longitude
 * @param float     $radius         The radius
 * @param string    $type           The type of entity (eg "user", "object" etc)
 * @param string    $subtype        The arbitrary subtype of the entity
 * @param int       $owner_guid     The GUID of the owning user
 * @param string    $order_by       The field to order by; by default, time_created desc
 * @param int       $limit          The number of entities to return; 10 by default
 * @param int       $offset         The indexing offset, 0 by default
 * @param boolean   $count          Count entities
 * @param int       $site_guid      Site GUID. 0 for current, -1 for any
 * @param int|array $container_guid Container GUID
 *
 * @return array A list of entities.
 * @deprecated 1.8
 */
function get_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = NULL) {
	elgg_deprecated_notice('get_entities_in_area() was deprecated by elgg_get_entities_from_location()!', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_location($options);
}

/**
 * Return entities within a given geographic area.
 *
 * Also accepts all options available to elgg_get_entities().
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	latitude => FLOAT Latitude of the location
 *
 * 	longitude => FLOAT Longitude of the location
 *
 *  distance => FLOAT/ARR (
 *						latitude => float,
 *						longitude => float,
 *					)
 *					The distance in degrees that determines the search box. A
 *					single float will result in a square in degrees.
 * @warning The Earth is round.
 *
 * @see ElggEntity::setLatLong()
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_entities_from_location(array $options = array()) {

	global $CONFIG;
	
	if (!isset($options['latitude']) || !isset($options['longitude']) ||
		!isset($options['distance'])) {
		return false;
	}

	if (!is_array($options['distance'])) {
		$lat_distance = (float)$options['distance'];
		$long_distance = (float)$options['distance'];
	} else {
		$lat_distance = (float)$options['distance']['latitude'];
		$long_distance = (float)$options['distance']['longitude'];
	}

	$lat = (float)$options['latitude'];
	$long = (float)$options['longitude'];
	$lat_min = $lat - $lat_distance;
	$lat_max = $lat + $lat_distance;
	$long_min = $long - $long_distance;
	$long_max = $long + $long_distance;

	$where = array();
	$wheres[] = "lat_name.string='geo:lat'";
	$wheres[] = "lat_value.string >= $lat_min";
	$wheres[] = "lat_value.string <= $lat_max";
	$wheres[] = "lon_name.string='geo:long'";
	$wheres[] = "lon_value.string >= $long_min";
	$wheres[] = "lon_value.string <= $long_max";

	$joins = array();
	$joins[] = "JOIN {$CONFIG->dbprefix}metadata lat on e.guid=lat.entity_guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lat_name on lat.name_id=lat_name.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lat_value on lat.value_id=lat_value.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metadata lon on e.guid=lon.entity_guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lon_name on lon.name_id=lon_name.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lon_value on lon.value_id=lon_value.id";

	// merge wheres to pass to get_entities()
	if (isset($options['wheres']) && !is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	} elseif (!isset($options['wheres'])) {
		$options['wheres'] = array();
	}
	$options['wheres'] = array_merge($options['wheres'], $wheres);

	// merge joins to pass to get_entities()
	if (isset($options['joins']) && !is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	} elseif (!isset($options['joins'])) {
		$options['joins'] = array();
	}
	$options['joins'] = array_merge($options['joins'], $joins);

	return elgg_get_entities_from_relationship($options);
}

/**
 * List entities in a given location
 *
 * @param string $location       Location
 * @param string $type           The type of entity (eg "user", "object" etc)
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view
 * @param bool   $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @deprecated 1.8
 */
function list_entities_location($location, $type= "", $subtype = "", $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_location() was deprecated. Use elgg_list_entities_from_metadata()', 1.8);

	return list_entities_from_metadata('location', $location, $type, $subtype, $owner_guid, $limit,
		$fullview, $listtypetoggle, $navigation);
}

/**
 * Returns a viewable list of entities from location
 *
 * @param array $options
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_location()
 *
 * @return string The viewable list of entities
 * @since 1.8.0
 */
function elgg_list_entities_from_location(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_location');
}

/**
 * List items within a given geographic area.
 *
 * @param real   $lat            Latitude
 * @param real   $long           Longitude
 * @param real   $radius         The radius
 * @param string $type           The type of entity (eg "user", "object" etc)
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view
 * @param bool   $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @deprecated 1.8
 */
function list_entities_in_area($lat, $long, $radius, $type= "", $subtype = "", $owner_guid = 0,
$limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_in_area() was deprecated. Use elgg_list_entities_from_location()', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities_from_location($options);
}

// Some distances in degrees (approximate)
// @todo huh? see warning on elgg_get_entities_from_location()
define("MILE", 0.01515);
define("KILOMETER", 0.00932);
