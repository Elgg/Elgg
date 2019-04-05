<?php
/**
 * Elgg river.
 * Activity stream functions.
 *
 * @package    Elgg.Core
 * @subpackage River
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
 * @throws DatabaseException
 */
function elgg_create_river_item(array $options = []) {

	$view = elgg_extract('view', $options, '');
	// use default viewtype for when called from web services api
	if (!empty($view) && !elgg_view_exists($view, 'default')) {
		return false;
	}

	$action_type = elgg_extract('action_type', $options);
	if (empty($action_type)) {
		return false;
	}

	$subject_guid = elgg_extract('subject_guid', $options, elgg_get_logged_in_user_guid());
	if (!($subject = get_entity($subject_guid))) {
		return false;
	}

	$object_guid = elgg_extract('object_guid', $options, 0);
	if (!($object = get_entity($object_guid))) {
		return false;
	}

	$target_guid = elgg_extract('target_guid', $options, 0);
	if ($target_guid) {
		// target_guid is not a required parameter so check
		// it only if it is included in the parameters
		if (!($target = get_entity($target_guid))) {
			return false;
		}
	}

	$posted = elgg_extract('posted', $options, time());

	$annotation_id = elgg_extract('annotation_id', $options, 0);
	if ($annotation_id) {
		if (!elgg_get_annotation_from_id($annotation_id)) {
			return false;
		}
	}

	$return_item = elgg_extract('return_item', $options, false);

	$values = [
		'action_type' => $action_type,
		'view' => $view,
		'subject_guid' => $subject_guid,
		'object_guid' => $object_guid,
		'target_guid' => $target_guid,
		'annotation_id' => $annotation_id,
		'posted' => $posted,
	];
	$col_types = [
		'action_type' => ELGG_VALUE_STRING,
		'view' => ELGG_VALUE_STRING,
		'subject_guid' => ELGG_VALUE_INTEGER,
		'object_guid' => ELGG_VALUE_INTEGER,
		'target_guid' => ELGG_VALUE_INTEGER,
		'annotation_id' => ELGG_VALUE_INTEGER,
		'posted' => ELGG_VALUE_INTEGER,
	];

	// return false to stop insert
	$values = elgg_trigger_plugin_hook('creating', 'river', null, $values);
	if ($values == false) {
		// inserting did not fail - it was just prevented
		return true;
	}

	$qb = \Elgg\Database\Insert::intoTable('river');
	foreach ($values as $name => $value) {
		$query_params[$name] = $qb->param($value, $col_types[$name]);
	}
	$qb->values($query_params);

	$id = _elgg_services()->db->insertData($qb);
	if (!$id) {
		return false;
	}

	if (!$return_item) {
		return $id;
	}

	$ia = elgg_set_ignore_access(true);
	$item = elgg_get_river_item_from_id($id);
	elgg_set_ignore_access($ia);

	if (!$item) {
		return false;
	}

	elgg_trigger_event('created', 'river', $item);

	return $item;
}

/**
 * Get river items
 *
 * @note If using types and subtypes in a query, they are joined with an AND.
 *
 * @param array $options Parameters:
 *   ids                  => INT|ARR River item id(s)
 *   subject_guids        => INT|ARR Subject guid(s)
 *   object_guids         => INT|ARR Object guid(s)
 *   target_guids         => INT|ARR Target guid(s)
 *   action_types         => STR|ARR The river action type(s) identifier
 *   posted_time_lower    => INT     The lower bound on the time posted
 *   posted_time_upper    => INT     The upper bound on the time posted
 *
 *   Additionally accepts all "annotation_*" options supported by {@link elgg_get_entities()}
 *   annotation_ids       => INT|ARR The identifier of the annotation(s)
 *
 *   types                => STR|ARR Entity type string(s)
 *   subtypes             => STR|ARR Entity subtype string(s)
 *   type_subtype_pairs   => ARR     Array of type => subtype pairs where subtype
 *                                   can be an array of subtype strings
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
 *   batch                => BOOL    If set to true, an Elgg\BatchResult object will be returned
 *                                   instead of an array. (false) Since 2.3.
 *
 *   batch_inc_offset     => BOOL    If "batch" is used, this tells the batch to increment the offset
 *                                   on each fetch. This must be set to false if you delete the batched
 *                                   results. (true)
 *
 *   batch_size           => INT     If "batch" is used, this is the number of entities/rows to pull
 *                                   in before requesting more. (25)
 *
 * @return \ElggRiverItem[]|\Elgg\BatchResult|array|int
 * @since 1.8.0
 */
function elgg_get_river(array $options = []) {
	return \Elgg\Database\River::find($options);
}

/**
 * Get river item from its ID
 *
 * @param int $id ID
 * @return ElggRiverItem|false
 */
function elgg_get_river_item_from_id($id) {
	$items = elgg_get_river([
		'id' => $id,
	]);

	return $items ? $items[0] : false;
}

/**
 * Delete river items based on $options.
 *
 * @warning Unlike elgg_get_river() this will not accept an empty options array!
 *          This requires at least one constraint: id(s), annotation_id(s)
 *          subject_guid(s), object_guid(s), target_guid(s)
 *          or view(s) must be set.
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
}

/**
 * List river items
 *
 * @param array $options Any options from elgg_get_river() plus:
 *   item_view  => STR         Alternative view to render list items
 *   pagination => BOOL        Display pagination links (true)
 *   no_results => STR|true|Closure Message to display if no items
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_river(array $options = []) {
	$defaults = [
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', max(20, _elgg_config()->default_limit)), 0),
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

/**
 * Register river unit tests
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $value current return value
 *
 * @return array
 * @codeCoverageIgnore
 */
function _elgg_river_test($hook, $type, $value) {
	$value[] = ElggCoreRiverAPITest::class;
	return $value;
}

/**
 * Updates the last action of the object of an river item
 *
 * @param string         $event 'create'
 * @param string         $type  'river'
 * @param \ElggRiverItem $item  The entity being disabled
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_update_object_last_action($event, $type, $item) {
	if (!$item instanceof \ElggRiverItem) {
		return;
	}
	
	$object = $item->getObjectEntity();
	if (!$object) {
		return;
	}
	
	$object->updateLastAction($item->getTimePosted());
}

/**
 * Disable river entries that reference a disabled entity as subject/object/target
 *
 * @param string     $event  'disable'
 * @param string     $type   'all'
 * @param ElggEntity $entity The entity being disabled
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_disable($event, $type, $entity) {

	if (!$entity instanceof ElggEntity) {
		return;
	}

	$dbprefix = _elgg_config()->dbprefix;
	$query = <<<QUERY
	UPDATE {$dbprefix}river AS rv
	SET rv.enabled = 'no'
	WHERE (rv.subject_guid = {$entity->guid} OR rv.object_guid = {$entity->guid} OR rv.target_guid = {$entity->guid});
QUERY;

	elgg()->db->updateData($query);
	return;
}


/**
 * Enable river entries that reference a re-enabled entity as subject/object/target
 *
 * @param string     $event  'enable'
 * @param string     $type   'all'
 * @param ElggEntity $entity The entity being enabled
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_enable($event, $type, $entity) {

	if (!$entity instanceof ElggEntity) {
		return;
	}

	$dbprefix = _elgg_config()->dbprefix;
	$query = <<<QUERY
	UPDATE {$dbprefix}river AS rv
	LEFT JOIN {$dbprefix}entities AS se ON se.guid = rv.subject_guid
	LEFT JOIN {$dbprefix}entities AS oe ON oe.guid = rv.object_guid
	LEFT JOIN {$dbprefix}entities AS te ON te.guid = rv.target_guid
	SET rv.enabled = 'yes'
	WHERE (
			(se.enabled = 'yes' OR se.guid IS NULL) AND
			(oe.enabled = 'yes' OR oe.guid IS NULL) AND
			(te.enabled = 'yes' OR te.guid IS NULL)
		)
		AND (se.guid = {$entity->guid} OR oe.guid = {$entity->guid} OR te.guid = {$entity->guid});
QUERY;

	elgg()->db->updateData($query);
	return;
}

/**
 * Add the delete to river actions menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:river'
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 */
function _elgg_river_menu_setup(\Elgg\Hook $hook) {
	if (!elgg_is_logged_in()) {
		return;
	}

	$item = $hook->getParam('item');
	if (!($item instanceof ElggRiverItem)) {
		return;
	}

	if (!$item->canDelete()) {
		return;
	}

	$return = $hook->getValue();

	$return[] = \ElggMenuItem::factory([
		'name' => 'delete',
		'href' => "action/river/delete?id={$item->id}",
		'is_action' => true,
		'icon' => 'delete',
		'text' => elgg_echo('river:delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 999,
	]);

	return $return;
}

/**
 * Initialize river library
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_river_test');

	elgg_register_plugin_hook_handler('register', 'menu:river', '_elgg_river_menu_setup');
	
	elgg_register_event_handler('created', 'river', '_elgg_river_update_object_last_action');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_river_init');
	$events->registerHandler('disable:after', 'all', '_elgg_river_disable', 600);
	$events->registerHandler('enable:after', 'all', '_elgg_river_enable', 600);
};
