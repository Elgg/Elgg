<?php

$container_guid = (int)get_input('container_guid');
if ($container_guid) {
	$container = get_entity($container_guid);

	if (elgg_instanceof($container, 'group') && $container->isMember()) {
		// embedding inside a group so save file to group files
		elgg_set_page_owner_guid($container_guid);
	}
}

set_input('page', $page[1]); 

echo elgg_view('embed/layout');
