<?php
/**
 * Elgg access level input
 * Displays a dropdown input field
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options_values'] Array of value => label pairs (overrides default)
 * @uses $vars['name']           The name of the input field
 * @uses $vars['entity']         Optional. The entity for this access control (uses access_id)
 * @uses $vars['class']          Additional CSS class
 *
 * @uses $vars['entity_type']    Optional. Type of the entity
 * @uses $vars['entity_subtype'] Optional. Subtype of the entity
 * @uses $vars['container_guid'] Optional. Container GUID of the entity
 *
 */

$vars['class'] = trim("elgg-input-access " . elgg_extract('class', $vars, ''));

// will be passed to plugin hooks ['access:collections:write', 'user'] and ['default', 'access']
$params = array();

$keys = array(
	'entity' => null,
	'entity_type' => null,
	'entity_subtype' => null,
	'container_guid' => null,
	'purpose' => 'read',
);
foreach ($keys as $key => $default_value) {
	$params[$key] = elgg_extract($key, $vars, $default_value);
	unset($vars[$key]);
}

/* @var ElggEntity $entity */
$entity = $params['entity'];

if ($entity) {
	$params['entity_type'] = $entity->type;
	$params['entity_subtype'] = $entity->getSubtype();
	$params['container_guid'] = $entity->container_guid;
}

$container = elgg_get_page_owner_entity();
if (!$params['container_guid'] && $container) {
	$params['container_guid'] = $container->guid;
}

$defaults = array(
	'disabled' => false,
	'value' => get_default_access(null, $params),
	'options_values' => get_write_access_array(0, 0, false, $params),
);

// should we tell users that public/logged-in access levels will be ignored?
if (($container instanceof ElggGroup)
		&& $container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY
		&& !elgg_in_context('group-edit')
		&& !($entity instanceof ElggGroup)) {
	$show_override_notice = true;
} else {
	$show_override_notice = false;
}

if ($entity) {
	$defaults['value'] = $entity->access_id;
}

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = get_default_access(null, $params);
}

if (empty($vars['options_values'])) {
	return;
}

if ($show_override_notice) {
	$vars['data-group-acl'] = $container->group_acl;
}
echo elgg_view('input/select', $vars);
if ($show_override_notice) {
	echo "<p class='elgg-text-help'>" . elgg_echo('access:overridenotice')  .  "</p>";
}
