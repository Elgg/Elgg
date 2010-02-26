<?php
/**
 * Elgg tags
 * Functions for managing tags and tag clouds.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.org/
 */


/**
 * The algorithm working out the size of font based on the number of tags.
 * This is quick and dirty.
 */
function calculate_tag_size($min, $max, $number_of_tags, $buckets = 6) {
	$delta =  (($max - $min) / $buckets);
	$thresholds = array();

	for ($n=1; $n <= $buckets; $n++) {
		$thresholds[$n-1] = ($min + $n) * $delta;
	}

	// Correction
	if ($thresholds[$buckets-1]>$max) {
		$thresholds[$buckets-1] = $max;
	}

	$size = 0;
	for ($n = 0; $n < count($thresholds); $n++) {
		if ($number_of_tags >= $thresholds[$n]) {
			$size = $n;
		}
	}

	return $size;
}

/**
 * This function generates an array of tags with a weighting.
 *
 * @param array $tags The array of tags.
 * @return An associated array of tags with a weighting, this can then be mapped to a display class.
 */
function generate_tag_cloud(array $tags, $buckets = 6) {
	$cloud = array();

	$min = 65535;
	$max = 0;

	foreach ($tags as $tag) {
		$cloud[$tag]++;

		if ($cloud[$tag]>$max) {
			$max = $cloud[$tag];
		}

		if ($cloud[$tag]<$min) {
			$min = $cloud[$tag];
		}
	}

	foreach ($cloud as $k => $v) {
		$cloud[$k] = calculate_tag_size($min, $max, $v, $buckets);
	}

	return $cloud;
}

/**
 * Get an array of tags with weights for use with the output/tagcloud view.
 *
 * @param int $threshold Get the threshold of minimum number of each tags to bother with (ie only show tags where there are more than $threshold occurances)
 * @param int $limit Number of tags to return
 * @param string $metadata_name Optionally, the name of the field you want to grab for
 * @param string $entity_type Optionally, the entity type ('object' etc)
 * @param string $entity_subtype The entity subtype, optionally
 * @param int $owner_guid The GUID of the tags owner, optionally
 * @param int $site_guid Optionally, the site to restrict to (default is the current site)
 * @param int $start_ts Optionally specify a start timestamp for tags used to generate cloud.
 * @param int $ent_ts Optionally specify an end timestamp for tags used to generate cloud.
 * @return array|false Array of objects with ->tag and ->total values, or false on failure
 */

function get_tags($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object", $entity_subtype = "", $owner_guid = "", $site_guid = -1, $start_ts = "", $end_ts = "") {
	global $CONFIG;

	$threshold = (int) $threshold;
	$limit = (int) $limit;

	$registered_tags = elgg_get_registered_tag_metadata_names();
	if (!in_array($metadata_name, $registered_tags)) {
		elgg_deprecated_notice('Tag metadata names must be registered by elgg_register_tag_metadata_name()', 1.7);
	}

	if (!empty($metadata_name)) {
		$metadata_name = (int) get_metastring_id($metadata_name);
		// test if any metadata with that name
		if (!$metadata_name) {
			return false; // no matches so short circuit
		}
	} else {
		$metadata_name = 0;
	}
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$entity_type = sanitise_string($entity_type);

	if ($owner_guid != "") {
		if (is_array($owner_guid)) {
			foreach($owner_guid as $key => $val) {
				$owner_guid[$key] = (int) $val;
			}
		} else {
			$owner_guid = (int) $owner_guid;
		}
	}

	if ($site_guid < 0) {
		$site_guid = $CONFIG->site_id;
	}

	$query = "SELECT msvalue.string as tag, count(msvalue.id) as total ";
	$query .= "FROM {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}metadata md on md.entity_guid = e.guid ";
	if ($entity_subtype > 0) {
		$query .= " join {$CONFIG->dbprefix}entity_subtypes subtype on subtype.id = e.subtype ";
	}
	$query .= " join {$CONFIG->dbprefix}metastrings msvalue on msvalue.id = md.value_id ";

	$query .= " where msvalue.string != '' ";

	if ($metadata_name > 0) {
		$query .= " and md.name_id = {$metadata_name} ";
	}
	if ($site_guid > 0) {
		$query .= " and e.site_guid = {$site_guid} ";
	}
	if ($entity_subtype > 0) {
		$query .= " and e.subtype = {$entity_subtype} ";
	}
	if ($entity_type != "") {
		$query .= " and e.type = '{$entity_type}' ";
	}
	if (is_array($owner_guid)) {
		$query .= " and e.container_guid in (".implode(",",$owner_guid).")";
	} else if (is_int($owner_guid)) {
		$query .= " and e.container_guid = {$owner_guid} ";
	}
	if ($start_ts) {
		$start_ts = (int)$start_ts;
		$query .= " and e.time_created>=$start_ts";
	}

	if ($end_ts) {
		$end_ts = (int)$end_ts;
		$query .= " and e.time_created<=$end_ts";
	}

	// Add access controls
	$query .= ' and ' . get_access_sql_suffix("e");

	$query .= " group by msvalue.string having total > {$threshold} order by total desc limit {$limit} ";

	return get_data($query);
}

/**
 * Loads and displays a tagcloud given particular criteria.
 *
 * @param int $threshold Get the threshold of minimum number of each tags to bother with (ie only show tags where there are more than $threshold occurances)
 * @param int $limit Number of tags to return
 * @param string $metadata_name Optionally, the name of the field you want to grab for
 * @param string $entity_type Optionally, the entity type ('object' etc)
 * @param string $entity_subtype The entity subtype, optionally
 * @param int $owner_guid The GUID of the tags owner, optionally
 * @param int $site_guid Optionally, the site to restrict to (default is the current site)
 * @param int $start_ts Optionally specify a start timestamp for tags used to generate cloud.
 * @param int $ent_ts Optionally specify an end timestamp for tags used to generate cloud.
 * @return string THe HTML (or other, depending on view type) of the tagcloud.
 */

function display_tagcloud($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object", $entity_subtype = "", $owner_guid = "", $site_guid = -1, $start_ts = "", $end_ts = "") {

	$registered_tags = elgg_get_registered_tag_metadata_names();
	if (!in_array($metadata_name, $registered_tags)) {
		elgg_deprecated_notice('Tag metadata names must be registered by elgg_register_tag_metadata_name()', 1.7);
	}

	return elgg_view("output/tagcloud",array('value' => get_tags($threshold, $limit, $metadata_name, $entity_type, $entity_subtype, $owner_guid, $site_guid, $start_ts, $end_ts),
											'object' => $entity_type,
											'subtype' => $entity_subtype));
}

/**
 * Registers a metadata name as containing tags for an entity.
 * This is required if you are using a non-standard metadata name
 * for your tags.
 *
 * @since 1.7
 *
 * @param string $name
 * @return TRUE
 */
function elgg_register_tag_metadata_name($name) {
	global $CONFIG;

	if (!isset($CONFIG->registered_tag_metadata_names)) {
		$CONFIG->registered_tag_metadata_names = array();
	}

	if (!in_array($name, $CONFIG->registered_tag_metadata_names)) {
		$CONFIG->registered_tag_metadata_names[] = $name;
	}

	return TRUE;
}

/**
 * Returns an array of valid metadata names for tags.
 *
 * @since 1.7
 *
 * @return array
 */
function elgg_get_registered_tag_metadata_names() {
	global $CONFIG;

	$names = (isset($CONFIG->registered_tag_metadata_names)) ? $CONFIG->registered_tag_metadata_names : array();
	return $names;
}

// register the standard tags metadata name
elgg_register_tag_metadata_name('tags');
