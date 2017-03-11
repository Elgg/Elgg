<?php

/**
 * Alias of elgg_delete_river() that doesn't raise notices
 *
 * @param array $options Options for elgg_delete_river()
 * @return bool
 * @internal Will be removed with elgg_delete_river()
 * @access private
 */
function _elgg_delete_river(array $options = []) {
	$options['__bypass_notice'] = true;
	return elgg_delete_river($options);
}

/**
 * Delete river items
 *
 * @warning Does not fire permission hooks or delete, river events.
 *
 * @param array $options Parameters:
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   target_guids         => INT|ARR Target guid(s)
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   views                => STR|ARR River view(s)
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
 *
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 2.3 Use elgg_get_river() and call delete() on the returned item(s)
 */
function elgg_delete_river(array $options = array()) {
	global $CONFIG;

	// allow core to use this in 2.x w/o warnings
	if (empty($options['__bypass_notice'])) {
		$msg = __FUNCTION__ . ' is deprecated. Use elgg_get_river() and call delete() on the returned item(s)';
		if (isset($options['view']) || isset($options['views'])) {
			$msg .= '. You must use the "wheres" option to specify value(s) for the "rv.view" column.';
		}
		elgg_deprecated_notice($msg, '2.3');
	}

	$defaults = array(
		'ids'                  => ELGG_ENTITIES_ANY_VALUE,

		'subject_guids'	       => ELGG_ENTITIES_ANY_VALUE,
		'object_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'target_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'annotation_ids'       => ELGG_ENTITIES_ANY_VALUE,

		'views'                => ELGG_ENTITIES_ANY_VALUE,
		'action_types'         => ELGG_ENTITIES_ANY_VALUE,

		'types'	               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'             => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'   => ELGG_ENTITIES_ANY_VALUE,

		'posted_time_lower'	   => ELGG_ENTITIES_ANY_VALUE,
		'posted_time_upper'	   => ELGG_ENTITIES_ANY_VALUE,

		'wheres'               => array(),
		'joins'                => array(),

	);

	$options = array_merge($defaults, $options);

	$singulars = array('id', 'subject_guid', 'object_guid', 'target_guid', 'annotation_id', 'action_type', 'view', 'type', 'subtype');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$wheres = $options['wheres'];

	$wheres[] = _elgg_get_guid_based_where_sql('rv.id', $options['ids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.subject_guid', $options['subject_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.object_guid', $options['object_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.target_guid', $options['target_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('rv.annotation_id', $options['annotation_ids']);
	$wheres[] = _elgg_river_get_action_where_sql($options['action_types']);
	$wheres[] = _elgg_river_get_view_where_sql($options['views']);
	$wheres[] = _elgg_get_river_type_subtype_where_sql('rv', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	if ($options['posted_time_lower'] && is_int($options['posted_time_lower'])) {
		$wheres[] = "rv.posted >= {$options['posted_time_lower']}";
	}

	if ($options['posted_time_upper'] && is_int($options['posted_time_upper'])) {
		$wheres[] = "rv.posted <= {$options['posted_time_upper']}";
	}

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === false) {
			return false;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	$query = "DELETE rv.* FROM {$CONFIG->dbprefix}river rv ";

	// remove identical join clauses
	$joins = array_unique($options['joins']);

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}
	$query .= "1=1";

	return delete_data($query);
}
