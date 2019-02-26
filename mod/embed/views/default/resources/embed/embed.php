<?php
/**
 * This resource view is use to show the embedable content in a lightbox
 */

$container_guid = (int) get_input('container_guid');
if ($container_guid) {
	$container = get_entity($container_guid);

	if ($container instanceof ElggGroup && $container->isMember()) {
		// embedding inside a group so save file to group files
		elgg_set_page_owner_guid($container_guid);
	}
}

echo elgg_view('embed/layout', $vars);
