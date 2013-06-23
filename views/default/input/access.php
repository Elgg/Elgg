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
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-access {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-access";
}

$defaults = array(
	'disabled' => false,
	'value' => get_default_access(),
	'options_values' => get_write_access_array(),
);

/* @var ElggEntity $entity */
$entity = elgg_extract('entity', $vars);
unset($vars['entity']);

// should we tell users that public/logged-in access levels will be ignored?
$container = elgg_get_page_owner_entity();
if (($container instanceof ElggGroup)
		&& $container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY
		&& !elgg_in_context('group-edit')
		&& !($entity && $entity instanceof ElggGroup)) {
	$show_override_notice = true;
} else {
	$show_override_notice = false;
}

if ($entity) {
	$defaults['value'] = $entity->access_id;
}

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = get_default_access();
}

if (is_array($vars['options_values']) && sizeof($vars['options_values']) > 0) {
	if ($show_override_notice) {
		$vars['data-group-acl'] = $container->group_acl;
	}
	echo elgg_view('input/select', $vars);
	if ($show_override_notice) {
		echo "<p class='elgg-text-help'>" . elgg_echo('access:overridenotice')  .  "</p>";
	}
}
