<?php
/**
 * Elgg river.
 * Activity stream functions.
 *
 * @package    Elgg.Core
 * @subpackage River
 */

/**
 * Register a river event
 *
 * @param string $action  Event name
 *                        e.g. publish, create
 * @param string $type    Event object type
 *                        e.g. object, group, user, annotation or relationship
 * @param string $subtype Event object subtype
 *                        e.g. entity subtype, annotation name or relationship name
 *
 * @return void
 */
function elgg_register_river_event($action, $type, $subtype = null) {
	_elgg_services()->river->registerEvent($action, $type, $subtype);
}

/**
 * Unregister river event
 *
 * @param string $action  Event name
 * @param string $type    Event object type
 * @param string $subtype Event object subtype
 *
 * @return void
 */
function elgg_unregister_river_event($action, $type, $subtype = null) {
	_elgg_services()->river->unregisterEvent($action, $type, $subtype);
}

/**
 * Push items to river for registered events
 *
 * @param \Elgg\Event $event Event
 * @return void
 */
function _elgg_river_event_listener(\Elgg\Event $event) {
	_elgg_services()->river->handleEvent($event);
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
 *   result_ids           => INT|ARR IDs of result objects
 *   result_types         => STR|ARR Types of result objects
 *   result_subtypes      => STR|ARR Subtypes of result objects
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
	elgg_register_rss_link();

	$defaults = [
		'offset'     => (int) max(get_input('offset', 0), 0),
		'limit'      => (int) max(get_input('limit', max(20, _elgg_config()->default_limit)), 0),
		'pagination' => true,
		'list_class' => 'elgg-list-river',
	];

	$options = array_merge($defaults, $options);

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
 * Delete river entries when object is deleted
 *
 * @elgg_event delete:after all
 *
 * @param \Elgg\Event $event Event
 *
 * @return void
 * @access private
 */
function _elgg_river_delete(\Elgg\Event $event) {

	$object = $event->getObject();

	if (!$object instanceof ElggData) {
		return;
	}

	if ($object instanceof ElggEntity) {
		elgg_delete_river([
			'subject_guid' => $object->guid,
			'limit' => 0,
		]);

		elgg_delete_river([
			'object_guid' => $object->guid,
			'limit' => 0,
		]);

		elgg_delete_river([
			'target_guid' => $object->guid,
			'limit' => 0,
		]);

		elgg_delete_river([
			'result_id' => $object->guid,
			'result_type' => $object->type,
			'limit' => 0,
		]);
	} else {
		elgg_delete_river([
			'result_id' => $object->id,
			'result_type' => $object->getType(),
			'limit' => 0,
		]);
	}

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

	$qb = \Elgg\Database\Update::table('river');
	$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING));

	$qb->where($qb->compare('subject_guid', '=', $entity->guid, ELGG_VALUE_INTEGER))
		->orWhere($qb->compare('object_guid', '=', $entity->guid, ELGG_VALUE_INTEGER))
		->orWhere($qb->compare('target_guid', '=', $entity->guid, ELGG_VALUE_INTEGER))
		->orWhere($qb->merge([
			$qb->compare('result_id', '=', $entity->guid, ELGG_VALUE_INTEGER),
			$qb->compare('result_type', '=', $entity->type, ELGG_VALUE_STRING),
		]));

	elgg()->db->updateData($qb);
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
 * @elgg_plugin_hook register menu:river
 *
 * @param \Elgg\Hook $hook 'register' 'menu:river'
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_menu_setup(\Elgg\Hook $hook) {
	if (!elgg_is_logged_in()) {
		return;
	}

	$item = $hook->getParam('item');

	if (!$item instanceof ElggRiverItem) {
		return;
	}

	if (!$item->canDelete()) {
		return;
	}

	$menu = $hook->getValue();
	/* @var $menu \Elgg\Menu\MenuItems */

	$menu->add(ElggMenuItem::factory([
		'name' => 'delete',
		'href' => "action/river/delete?id={$item->id}",
		'is_action' => true,
		'icon' => 'delete',
		'text' => elgg_echo('river:delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 999,
	]));
}

/**
 * Initialize river library
 *
 * @return void
 *
 * @access private
 */
function _elgg_river_init() {
	elgg_register_plugin_hook_handler('register', 'menu:river', '_elgg_river_menu_setup');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_river_init');

	$events->registerHandler('all', 'all', '_elgg_river_event_listener');

	$events->registerHandler('delete:after', 'all', '_elgg_river_delete');
	$events->registerHandler('disable:after', 'all', '_elgg_river_disable', 600);
	$events->registerHandler('enable:after', 'all', '_elgg_river_enable', 600);
};
