<?php

$collection = elgg_extract('collection', $vars);
$members = get_members_of_access_collection($collection->id);


$list = elgg_view_entity_list($members, array(
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users elgg-gallery-fluid',
		));

echo elgg_format_element('div', ['class' => 'elgg-friends-collection-membership clearfix'], $list);