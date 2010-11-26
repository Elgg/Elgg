<?php
/**
 * Elgg tags
 * Functions for managing tags and tag clouds.
 *
 * @package Elgg
 * @subpackage Core
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
 * Get popular tags and their frequencies
 *
 * Supports similar arguments as elgg_get_entities()
 *
 *
 * @param array $options Array in format:
 *
 * 	threshold => INT minimum tag count
 *
 * 	tag_names => array() metadata tag names - must be registered tags
 *
 * 	limit => INT number of tags to return
 *
 *  types => NULL|STR entity type (SQL: type = '$type')
 *
 * 	subtypes => NULL|STR entity subtype (SQL: subtype = '$subtype')
 *
 * 	type_subtype_pairs => NULL|ARR (array('type' => 'subtype')) (SQL: type = '$type' AND subtype = '$subtype') pairs
 *
 * 	owner_guids => NULL|INT entity guid
 *
 * 	container_guids => NULL|INT container_guid
 *
 * 	site_guids => NULL (current_site)|INT site_guid
 *
 * 	created_time_lower => NULL|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => NULL|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => NULL|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => NULL|INT Modified time upper boundary in epoch time
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *
 * @return 	false/array - if no tags or error, false
 * 			otherwise, array of objects with ->tag and ->total values
 * @since 1.7.1
 */
function elgg_get_tags(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'threshold'				=>	1,
		'tag_names'				=>	array(),
		'limit'					=>	10,

		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	$CONFIG->site_guid,

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		'joins'					=>	array(),
		'wheres'				=>	array(),
	);


	$options = array_merge($defaults, $options);

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'site_guid');
	$options = elgg_normalise_plural_options_array($options, $singulars);


	$registered_tags = elgg_get_registered_tag_metadata_names();

	if (!is_array($options['tag_names'])) {
		return false;
	}

	// empty array so use all registered tag names
	if (count($options['tag_names']) == 0) {
		$options['tag_names'] = $registered_tags;
	}

	$diff = array_diff($options['tag_names'], $registered_tags);
	if (count($diff) > 0) {
		elgg_deprecated_notice('Tag metadata names must be registered by elgg_register_tag_metadata_name()', 1.7);
		// return false;
	}


	$wheres = $options['wheres'];

	// catch for tags that were spaces
	$wheres[] = "msv.string != ''";

	foreach ($options['tag_names'] as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}
	$tags_in = implode(',', $sanitised_tags);
	$wheres[] = "(msn.string IN ($tags_in))";

	$wheres[] = elgg_get_entity_type_subtype_where_sql('e', $options['types'], $options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = elgg_get_entity_site_where_sql('e', $options['site_guids']);
	$wheres[] = elgg_get_entity_owner_where_sql('e', $options['owner_guids']);
	$wheres[] = elgg_get_entity_container_where_sql('e', $options['container_guids']);
	$wheres[] = elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}


	$joins = $options['joins'];

	$joins[] = "JOIN {$CONFIG->dbprefix}metadata md on md.entity_guid = e.guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msv on msv.id = md.value_id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msn on md.name_id = msn.id";

	// remove identical join clauses
	$joins = array_unique($joins);

	foreach ($joins as $i => $join) {
		if ($join === FALSE) {
			return FALSE;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}


	$query  = "SELECT msv.string as tag, count(msv.id) as total ";
	$query .= "FROM {$CONFIG->dbprefix}entities e ";

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');

	$threshold = sanitise_int($options['threshold']);
	$query .= " GROUP BY msv.string HAVING total >= {$threshold} ";
	$query .= " ORDER BY total DESC ";

	$limit = sanitise_int($options['limit']);
	$query .= " LIMIT {$limit} ";

	return get_data($query);
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

	//elgg_deprecated_notice('get_tags() has been replaced by elgg_get_tags()', 1.8);

	if (is_array($metadata_name)) {
		return false;
	}

	$options = array();
	if ($metadata_name === '') {
		$options['tag_names'] = array();
	} else {
		$options['tag_names'] = array($metadata_name);
	}

	$options['threshold'] = $threshold;
	$options['limit'] = $limit;

	// rewrite owner_guid to container_guid to emulate old functionality
	$container_guid = $owner_guid;
	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($entity_type) {
		$options['type'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($site_guid != -1) {
		$options['site_guids'] = $site_guid;
	}

	if ($end_ts) {
		$options['created_time_upper'] = $end_ts;
	}

	if ($start_ts) {
		$options['created_time_lower'] = $start_ts;
	}

	$r = elgg_get_tags($options);
	return $r;
}

/**
 * Returns viewable tagcloud
 *
 *
 * @see elgg_get_tags
 *
 * @param array $options Any elgg_get_tags() options except:
 *
 * 	type => must be single entity type
 *
 * 	subtype => must be single entity subtype
 *
 * @return string
 * @since 1.7.1
 */
function elgg_view_tagcloud(array $options = array()) {

	$type = $subtype = '';
	if (isset($options['type'])) {
		$type = $options['type'];
	}
	if (isset($options['subtype'])) {
		$subtype = $options['subtype'];
	}
	
	$tag_data = elgg_get_tags($options);
	return elgg_view("output/tagcloud",array('value' => $tag_data,
											'type' => $type,
											'subtype' => $subtype));

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
 * @return string The HTML (or other, depending on view type) of the tagcloud.
 */

function display_tagcloud($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object", $entity_subtype = "", $owner_guid = "", $site_guid = -1, $start_ts = "", $end_ts = "") {

	//elgg_deprecated_notice('display_cloud() was deprecated by elgg_view_tagcloud()!', 1.8);
	
	return elgg_view("output/tagcloud",array('value' => get_tags($threshold, $limit, $metadata_name, $entity_type, $entity_subtype, $owner_guid, $site_guid, $start_ts, $end_ts),
											'type' => $entity_type,
											'subtype' => $entity_subtype));
}

/**
 * Registers a metadata name as containing tags for an entity.
 * This is required if you are using a non-standard metadata name
 * for your tags.
 *
 *
 * @param string $name
 * @return bool
 * @since 1.7.0
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
 * @return array
 * @since 1.7.0
 */
function elgg_get_registered_tag_metadata_names() {
	global $CONFIG;

	$names = (isset($CONFIG->registered_tag_metadata_names)) ? $CONFIG->registered_tag_metadata_names : array();
	return $names;
}

// register the standard tags metadata name
elgg_register_tag_metadata_name('tags');
