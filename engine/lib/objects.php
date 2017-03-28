<?php

/**
 * Elgg objects
 * Functions to manage multiple or single objects in an Elgg install
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Return the object specific details of a object by a row.
 *
 * @param int $guid The guid to retrieve
 *
 * @return bool
 * @access private
 */
function get_object_entity_as_row($guid) {
	$dbprefix = elgg_get_config('dbprefix');
	$sql = "SELECT * FROM {$dbprefix}objects_entity
		WHERE guid = :guid";
	$params = [
		':guid' => (int) $guid,
	];
	return _elgg_services()->db->getDataRow($sql, null, $params);
}

/**
 * Setup object imprint
 *
 * @param \Elgg\Hook $hook Hook
 * @return ElggMenuItem[]
 * @access private
 */
function _elgg_setup_object_imprint(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();

	if (!$entity instanceof \ElggObject) {
		return;
	}

	$vars = $hook->getParams();
	$menu = $hook->getValue();

	$byline = elgg_view('object/elements/byline', $vars);
	if ($byline) {
		$menu['byline'] = ElggMenuItem::factory([
					'name' => 'byline',
					'href' => false,
					'text' => $byline,
					'priority' => 100,
		]);
	}

	$time = elgg_view('object/elements/time', $vars);
	if ($time) {
		$menu['time'] = ElggMenuItem::factory([
					'name' => 'time',
					'href' => false,
					'text' => $time,
					'priority' => 200,
		]);
	}

	$access = elgg_view('object/elements/access', $vars);
	if ($access) {
		$menu['access'] = ElggMenuItem::factory([
					'name' => 'access',
					'href' => false,
					'text' => $access,
					'priority' => 300,
		]);
	}

	return $menu;
}

/**
 * Setup object meta block
 *
 * @param \Elgg\Hook $hook Hook
 * @return ElggMenuItem[]
 * @access private
 */
function _elgg_setup_object_meta_block(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();

	if (!$entity instanceof \ElggObject) {
		return;
	}

	$vars = $hook->getParams();
	$menu = $hook->getValue();

	$owner = $entity->getOwnerEntity();
	if ($owner) {
		$menu[] = ElggMenuItem::factory([
					'name' => 'owner',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('meta_block:owner'),
						'value' => elgg_view('output/url', [
							'href' => $owner->getURL(),
							'text' => $owner->getDisplayName(),
						])
					]),
					'priority' => 100,
		]);
	}

	$container = $entity->getContainerEntity();
	if ($container instanceof ElggGroup) {
		$menu[] = ElggMenuItem::factory([
					'name' => 'container',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('meta_block:group'),
						'value' => elgg_view('output/url', [
							'href' => $container->getURL(),
							'text' => $container->getDisplayName(),
						])
					]),
					'priority' => 200,
		]);
	}

	if ($entity->time_created) {
		$menu[] = ElggMenuItem::factory([
					'name' => 'time_created',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('meta_block:time_created'),
						'value' => date('j M, Y H:i', $entity->time_created),
					]),
					'priority' => 300,
		]);
	}

	if ($entity->time_updated) {
		$menu[] = ElggMenuItem::factory([
					'name' => 'time_updated',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('meta_block:time_updated'),
						'value' => date('j M, Y H:i', $entity->time_updated),
					]),
					'priority' => 400,
		]);
	}

	$menu[] = ElggMenuItem::factory([
					'name' => 'access',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('access'),
						'value' => elgg_view('output/access', [
							'entity' => $entity,
						]),
					]),
					'priority' => 400,
		]);

	if ($entity->tags) {
		$menu[] = ElggMenuItem::factory([
					'name' => 'tags',
					'href' => false,
					'text' => elgg_view('output/field', [
						'label' => elgg_echo('tags'),
						'value' => elgg_view('output/tags', [
							'entity' => $entity,
						])
					]),
					'priority' => 500,
		]);
	}

	return $menu;
}

/**
 * Runs unit tests for \ElggObject
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_objects_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggObjectTest.php";
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_objects_test');
	$hooks->registerHandler('register', 'menu:entity_imprint', '_elgg_setup_object_imprint');
	$hooks->registerHandler('register', 'menu:meta_block', '_elgg_setup_object_meta_block');
};
