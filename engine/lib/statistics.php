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

use Elgg\Database\Clauses\OrderByClause;

/**
 * Return an array reporting the number of various entities in the system.
 *
 * @param int $owner_guid Optional owner of the statistics
 *
 * @return array
 */
function get_entity_statistics($owner_guid = 0) {

	$owner_guid = (int) $owner_guid;
	$entity_stats = [];

	$grouped_entities = elgg_get_entities([
		'selects' => ['COUNT(*) as cnt'],
		'owner_guids' => ($owner_guid) ? : ELGG_ENTITIES_ANY_VALUE,
		'group_by' => ['e.type', 'e.subtype'],
		'limit' => 0,
		'order_by' => new OrderByClause('cnt', 'DESC'),
	]);
	
	if (!empty($grouped_entities)) {
		foreach ($grouped_entities as $entity) {
			$type = $entity->getType();
			if (!isset($entity_stats[$type]) || !is_array($entity_stats[$type])) {
				$entity_stats[$type] = [];
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
 * @param string $type count for the type - unverified, banned, total, active (default)
 *
 * @return int
 */
function get_number_users($type = 'active') {
    switch ($type) {
        case 'unverified':
            $metadata_name_value_pairs = [
                ['name' => 'validated', 'value' => false],
            ];
            break;
        case 'banned':
            $metadata_name_value_pairs = [
                ['name' => 'banned', 'value' => 'yes'],
            ];
            break;
        case 'total':
            $metadata_name_value_pairs = [];
            break;
        default:
            $metadata_name_value_pairs = [
                ['name' => 'banned', 'value' => 'no'],
            ];
            break;
    }
    
    $hidden_status = access_get_show_hidden_status();
    
    if($type == 'total' || $type == 'unverified'){
        access_show_hidden_entities(true);
    }
    
    $result = elgg_get_entities([
        'type' => 'user',
        'sybtype' => 'user',
        'count'=> true,
        'metadata_name_value_pairs' => $metadata_name_value_pairs,
    ]);
    access_show_hidden_entities($hidden_status);
	
	return $result;
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
function get_online_users(array $options = []) {
	$options = array_merge([
		'seconds' => 600,
	], $options);

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

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', 'statistics_init');
};
