<?php
/**
 * Displays HTML for entity access levels.
 * Requires an entity because some special logic for containers is used.
 *
 * @uses int $vars['entity'] - The entity whose access ID to display.
 */

//sort out the access level for display
if (isset($vars['entity']) && elgg_instanceof($vars['entity'])) {
	$access_id = $vars['entity']->access_id;
	$access_class = 'elgg-access';
	$access_id_string = get_readable_access_level($access_id);

	// if within a group or shared access collection display group name and open/closed membership status
	// @todo have a better way to do this instead of checking against subtype / class.
	$container = $vars['entity']->getContainerEntity();

	if ($container && $container instanceof ElggGroup) {
		// we decided to show that the item is in a group, rather than its actual access level
		// not required. Group ACLs are prepended with "Group: " when written.
		//$access_id_string = elgg_echo('groups:group') . $container->name;
		$membership = $is_group->membership;

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

	echo "<span class=\"$access_class\">$access_id_string</span>";
}
