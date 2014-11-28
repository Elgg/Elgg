<?php

/**
 * Displays HTML with human readable representation of an access level
 *
 * @uses ElggEntity $vars['entity'] Optional. The entity whose access ID to display. If provided, additional logic is used to determine CSS classes
 * @uses int        $vars['value']  Optional. Access ID to display.
 */
$access_class = 'elgg-access';

//sort out the access level for display
if (isset($vars['entity']) && elgg_instanceof($vars['entity'])) {
	$access_id = $vars['entity']->access_id;

	// if within a group or shared access collection display group name and open/closed membership status
	// @todo have a better way to do this instead of checking against subtype / class.
	$container = $vars['entity']->getContainerEntity();

	if ($container && $container instanceof ElggGroup) {
		// we decided to show that the item is in a group, rather than its actual access level
		// not required. Group ACLs are prepended with "Group: " when written.
		//$access_id_string = elgg_echo('groups:group') . $container->name;
		$membership = $container->membership;

		if ($membership == ACCESS_PUBLIC) {
			$access_class .= ' elgg-access-group-open';
		} else {
			$access_class .= ' elgg-access-group-closed';
		}

		// @todo this is plugin specific code in core. Should be removed.
	} elseif ($container && $container->getSubtype() == 'shared_access') {
		$access_class .= ' shared_collection';
	} elseif ($access_id == ACCESS_PRIVATE) {
		$access_class .= ' elgg-access-private';
	}
} else if (isset($vars['value'])) {
	$access_id = $vars['value'];
}

if (!isset($access_id)) {
	return true;
}

$access_id_string = get_readable_access_level($access_id);

$attributes = array(
	'title' => elgg_echo('access:help'),
	'class' => $access_class,
);

echo elgg_format_element('span', $attributes, $access_id_string, array(
	'encode_text' => true,
));