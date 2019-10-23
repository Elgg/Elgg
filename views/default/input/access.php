<?php
/**
 * Elgg access level input
 * Displays a dropdown input field
 *
 * @uses $vars['value']                  The current value, if any
 * @uses $vars['options_values']         Array of value => label pairs (overrides default)
 * @uses $vars['name']                   The name of the input field
 * @uses $vars['entity']                 Optional. The entity for this access control (uses access_id)
 * @uses $vars['class']                  Additional CSS class
 *
 * @uses $vars['entity_type']            Optional. Type of the entity
 * @uses $vars['entity_subtype']         Optional. Subtype of the entity
 * @uses $vars['container_guid']         Optional. Container GUID of the entity
 * @usee $vars['entity_allows_comments'] Optional. (bool) whether the entity uses comments - used for UI display of access change warnings
 *
 */

// bail if set to a unusable value
if (isset($vars['options_values'])) {
	if (!is_array($vars['options_values']) || empty($vars['options_values'])) {
		return;
	}
}

$entity_allows_comments = elgg_extract('entity_allows_comments', $vars, true);
unset($vars['entity_allows_comments']);

$vars['class'] = elgg_extract_class($vars, 'elgg-input-access');

// this will be passed to plugin hooks ['access:collections:write', 'user'] and ['default', 'access']
$params = [];

$keys = [
	'entity' => null,
	'entity_type' => null,
	'entity_subtype' => null,
	'container_guid' => null,
	'purpose' => 'read',
];
foreach ($keys as $key => $default_value) {
	$params[$key] = elgg_extract($key, $vars, $default_value);
	unset($vars[$key]);
}

/* @var ElggEntity $entity */
$entity = $params['entity'];

if ($entity) {
	$params['value'] = $entity->access_id;
	$params['entity_type'] = $entity->type;
	$params['entity_subtype'] = $entity->getSubtype();
	$params['container_guid'] = $entity->container_guid;

	if ($entity_allows_comments && ($entity->access_id != ACCESS_PUBLIC)) {
		$vars['data-comment-count'] = (int) $entity->countComments();
		$vars['data-original-value'] = $entity->access_id;
	}
}

$container = elgg_get_page_owner_entity();
if (!$params['container_guid'] && $container) {
	$params['container_guid'] = $container->guid;
}

// don't call get_default_access() unless we need it
$add_missing_value = true;
if (!isset($vars['value']) || $vars['value'] == ACCESS_DEFAULT) {
	if ($entity) {
		$vars['value'] = $entity->access_id;
	} else if (empty($vars['options_values']) || !is_array($vars['options_values'])) {
		$vars['value'] = get_default_access(null, $params);
		
		// if default value is not present in the options we should not add it
		$add_missing_value = false;
	} else {
		$options_values_ids = array_keys(elgg_extract('options_values', $vars));
		$vars['value'] = $options_values_ids[0];
	}
}

$params['value'] = elgg_extract('value', $vars);

// don't call get_write_access_array() unless we need it
if (!isset($vars['options_values'])) {
	$vars['options_values'] = get_write_access_array(0, 0, false, $params);
}

if (!isset($vars['disabled'])) {
	$vars['disabled'] = false;
}

// if access is set to a value not present in the available options, add the option
if ($add_missing_value && !isset($vars['options_values'][$vars['value']])) {
	$vars['options_values'][$vars['value']] = get_readable_access_level($vars['value']);
}

echo elgg_view('input/select', $vars);
