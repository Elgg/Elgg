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

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->access_id;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$page_owner = elgg_get_page_owner_entity();
	
	// Default access for entities in closed/invisible groups is the same group
	$is_group = elgg_instanceof($page_owner, 'group');
	$is_closed    = !in_array($page_owner->membership, array(ACCESS_PUBLIC, ACCESS_LOGGED_IN));
	$is_invisible = !in_array($page_owner->access_id,  array(ACCESS_PUBLIC, ACCESS_LOGGED_IN));
	
	if ($is_group && ($is_closed || $is_invisible)) {
		$vars['value'] = $page_owner->group_acl;
	} else {
		$vars['value'] = get_default_access();
	}
}

if (is_array($vars['options_values']) && sizeof($vars['options_values']) > 0) {
	echo elgg_view('input/dropdown', $vars);
}
