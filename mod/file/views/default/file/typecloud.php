<?php
/**
 * Type cloud
 */

function file_type_cloud_get_url($type, $friends) {
	$url = elgg_get_site_url() . "mod/file/search.php?subtype=file";

	if ($type->tag != "all") {
		$url .= "&md_type=simpletype&tag=" . urlencode($type->tag);
	}

	if ($friends) {
		$url .= "&friends=$friends";
	} 

	if ($type->tag == "image") {
		$url .= "&listtype=gallery";
	}

	if (elgg_get_page_owner_guid()) {
		$url .= "&page_owner=" . elgg_get_page_owner_guid();
	}

	return $url;
}


$types = elgg_get_array_value('types', $vars, array());
if (!$types) {
	return true;
}

$friends = elgg_get_array_value('friends', $vars, false);

$all = new stdClass;
$all->tag = "all";
elgg_register_menu_item('page', array(
	'name' => 'file:all',
	'title' => elgg_echo('all'),
	'url' =>  file_type_cloud_get_url($all, $friends),
));

foreach ($types as $type) {
	elgg_register_menu_item('page', array(
		'name' => "file:$type->tag",
		'title' => elgg_echo("file:type:$type->tag"),
		'url' =>  file_type_cloud_get_url($type, $friends),
	));
}
