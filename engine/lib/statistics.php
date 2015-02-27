<?php
/**
 * Elgg statistics library.
 *
 * This file contains a number of functions for obtaining statistics about the running system.
 * These statistics are mainly used by the administration pages, and is also where the basic
 * views for statistics are added.
 *
 * @package Elgg.Core
 * @subpackage Statistics
 */

/**
 * Return an array reporting the number of various entities in the system.
 *
 * @param int $owner_guid Optional owner of the statistics
 *
 * @return array
 */
function get_entity_statistics($owner_guid = 0) {

	$owner_guid = (int) $owner_guid;
	$entity_stats = array();

	$grouped_entities = elgg_get_entities(array(
		'selects' => array('COUNT(*) as cnt'),
		'owner_guids' => ($owner_guid) ? : ELGG_ENTITIES_ANY_VALUE,
		'group_by' => 'e.type, e.subtype',
		'limit' => 0,
		'order_by' => 'cnt DESC',
	));
	
	if (!empty($grouped_entities)) {
		foreach ($grouped_entities as $entity) {
			$type = $entity->getType();
			if (!isset($entity_stats[$type]) || !is_array($entity_stats[$type])) {
				$entity_stats[$type] = array();
			}
			$subtype = $entity->getSubtype();
			if (!$subtype) {
				$subtype = '__base__';
			}
			$entity_stats[$type][$subtype] = $entity->getVolatileData('select:cnt');
		}
	}

	return $entity_stats;
}

/**
 * Return the number of users registered in the system.
 *
 * @param bool $show_deactivated Count not enabled users?
 *
 * @return int
 */
function get_number_users($show_deactivated = false) {
	global $CONFIG;

	$access = "";

	if (!$show_deactivated) {
		$access = "and " . _elgg_get_access_where_sql(array('table_alias' => ''));
	}

	$query = "SELECT count(*) as count
		from {$CONFIG->dbprefix}entities where type='user' $access";

	$result = get_data_row($query);

	if ($result) {
		return $result->count;
	}

	return false;
}

/**
 * Render a list of currently online users
 *
 * @tip This also support options from elgg_list_entities().
 *
 * @param array $options Options array with keys:
 *
 *    seconds (int) => Number of seconds (default 600 = 10min)
 *
 * @return string
 */
function get_online_users(array $options = array()) {
	$options = array_merge(array(
		'seconds' => 600,
	), $options);

	return elgg_list_entities($options, 'find_active_users');
}

/**
 * Initialise the statistics admin page.
 *
 * @return void
 * @access private
 */
function statistics_init() {
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/online');
	elgg_extend_view('core/settings/statistics', 'core/settings/statistics/numentities');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', 'statistics_init');
};
