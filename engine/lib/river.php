<?php
/**
 * Elgg river.
 * Activity stream functions.
 */

/**
 * Adds an item to the river.
 *
 * @tip    Read the item like "Lisa (subject) posted (action)
 * a comment (object) on John's blog (target)".
 *
 * @param array $options Array in format:
 *
 * @option string $view The view that will handle the river item
 * @option string $action_type   An arbitrary string to define the action (eg 'comment', 'create')
 * @option int    $subject_guid  The GUID of the entity doing the action (default: current logged in user guid)
 * @option int    $object_guid   The GUID of the entity being acted upon
 * @option int    $target_guid   The GUID of the the object entity's container
 * @option int    $posted        The UNIX epoch timestamp of the river item (default: now)
 * @option int    $annotation_id The annotation ID associated with this river entry
 * @option bool   $return_item   set to true to return the ElggRiverItem created
 *
 * @return int|ElggRiverItem|bool River ID/item or false on failure
 * @since  1.9
 */
function elgg_create_river_item(array $options = []) {
	$item = new \ElggRiverItem();
	$item->action_type = elgg_extract('action_type', $options);
	$item->view = elgg_extract('view', $options);
	$item->subject_guid = elgg_extract('subject_guid', $options, elgg_get_logged_in_user_guid());
	$item->object_guid = elgg_extract('object_guid', $options);
	$item->target_guid = elgg_extract('target_guid', $options);
	$item->annotation_id = elgg_extract('annotation_id', $options);
	$item->posted = elgg_extract('posted', $options);
	
	if (!$item->save()) {
		return false;
	}
	
	return (bool) elgg_extract('return_item', $options, false) ? $item : $item->id;
}

/**
 * Get river items
 *
 * Supports passing the following options
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   target_guids         => INT|ARR Target guid(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *
 *   Additionally accepts all "annotation_*" options supported by {@link elgg_get_entities()} but not annotation_ids as that applies to the river table
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
 *
 *   @note If using types and subtypes in a query, they are joined with an AND.
 *
 *   Additionally accepts all "relationship_*" options supported by {@link elgg_get_entities()}
 *   relationship         => STR     Relationship identifier
 *   relationship_guid    => INT|ARR Entity guid(s)
 *   inverse_relationship => BOOL    Subject or object of the relationship (false)
 *   relationship_join_on => STR     subject_guid|object_guid|target_guid (defaults to subject_guid)
 *
 *   limit                => INT     Number to show per page (20)
 *   offset               => INT     Offset in list (0)
 *   count                => BOOL    Count the river items? (false)
 *   order_by             => STR     Order by clause (rv.posted desc)
 *   group_by             => STR     Group by clause
 *
 *   distinct             => BOOL    If set to false, Elgg will drop the DISTINCT
 *                                   clause from the MySQL query, which will improve
 *                                   performance in some situations. Avoid setting this
 *                                   option without a full understanding of the
 *                                   underlying SQL query Elgg creates. (true)
 *
 *   batch                => BOOL    If set to true, an \ElggBatch object will be returned
 *                                   instead of an array. (false) Since 2.3.
 *
 *   batch_inc_offset     => BOOL    If "batch" is used, this tells the batch to increment the offset
 *                                   on each fetch. This must be set to false if you delete the batched
 *                                   results. (true)
 *
 *   batch_size           => INT     If "batch" is used, this is the number of entities/rows to pull
 *                                   in before requesting more. (25)
 *
 * @param array $options parameters
 *
 * @return \ElggRiverItem[]|\ElggBatch|array|int
 * @since 1.8.0
 */
function elgg_get_river(array $options = []) {
	return \Elgg\Database\River::find($options);
}

/**
 * Get river item from its ID
 *
 * @param int $id ID
 *
 * @return ElggRiverItem|false
 */
function elgg_get_river_item_from_id(int $id) {
	return _elgg_services()->riverTable->get($id) ?? false;
}

/**
 * Delete river items based on $options.
 *
 * @warning Unlike elgg_get_river() this will not accept an empty options array!
 *          This requires at least one constraint: id(s), annotation_id(s)
 *          subject_guid(s), object_guid(s), target_guid(s)
 *          or view(s) must be set.
 *
 *          Access is ignored during the execution of this function.
 *          Intended usage of this function is to cleanup river content.
 *          For an example see actions/avatar/upload.
 *
 * @param array $options An options array. {@link elgg_get_river()}
 *
 * @return bool|null true on success, false on failure, null if no metadata to delete.
 *
 * @since   1.8.0
 */
function elgg_delete_river(array $options = []) {

	if (!_elgg_is_valid_options_for_batch_operation($options, 'river')) {
		// requirements not met
		return false;
	}

	return elgg_call(ELGG_IGNORE_ACCESS, function() use ($options) {
		$options['batch'] = true;
		$options['batch_size'] = 25;
		$options['batch_inc_offset'] = false;
	
		$river = elgg_get_river($options);
		$count = $river->count();
	
		if (!$count) {
			return;
		}
	
		$success = 0;
		foreach ($river as $river_item) {
			if ($river_item->delete()) {
				$success++;
			}
		}
	
		return $success == $count;
	});
}

/**
 * List river items
 *
 * @param array $options Any options from elgg_get_river() plus:
 *                       - item_view  => STR         Alternative view to render list items
 *                       - pagination => BOOL        Display pagination links (true)
 *                       - no_results => STR|true|Closure Message to display if no items
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_river(array $options = []) {
	$defaults = [
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', max(20, _elgg_services()->config->default_limit)), 0),
		'pagination' => true,
		'list_class' => 'elgg-list-river',
	];

	$options = array_merge($defaults, $options);
	
	$options['register_rss_link'] = elgg_extract('register_rss_link', $options, elgg_extract('pagination', $options));
	if ($options['register_rss_link']) {
		elgg_register_rss_link();
	}
	
	if (!$options["limit"] && !$options["offset"]) {
		// no need for pagination if listing is unlimited
		$options["pagination"] = false;
	}
	
	$options['count'] = false;
	$items = elgg_get_river($options);
	$options['count'] = is_array($items) ? count($items) : 0;
	
	if (!empty($items)) {
		$count_needed = true;
		if (!$options['pagination']) {
			$count_needed = false;
		} elseif (!$options['offset'] && !$options['limit']) {
			$count_needed = false;
		} elseif (($options['count'] < (int) $options['limit']) && !$options['offset']) {
			$count_needed = false;
		}
		
		if ($count_needed) {
			$options['count'] = true;
		
			$options['count'] = (int) elgg_get_river($options);
		}
	}
	
	$options['items'] = $items;

	return elgg_view('page/components/list', $options);
}
