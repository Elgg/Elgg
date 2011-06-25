<?php
/**
 * Type cloud
 */

function file_type_cloud_get_url($type, $friends) {
	$url = elgg_get_site_url() . 'file/search?subtype=file';

	if ($type->tag != "all") {
		$url .= "&md_type=simpletype&tag=" . urlencode($type->tag);
	}

	if ($friends) {
		$url .= "&friends=$friends";
	} 

	if ($type->tag == "image") {
		$url .= "&list_type=gallery";
	}

	if (elgg_get_page_owner_guid()) {
		$url .= "&page_owner=" . elgg_get_page_owner_guid();
	}

	return $url;
}


$types = elgg_extract('types', $vars, array());
if (!$types) {
	return true;
}

$friends = elgg_extract('friends', $vars, false);

$all = new stdClass;
$all->tag = "all";
elgg_register_menu_item('page', array(
	'name' => 'file:all',
	'text' => elgg_echo('all'),
	'href' =>  file_type_cloud_get_url($all, $friends),
));

foreach ($types as $type) {
	elgg_register_menu_item('page', array(
		'name' => "file:$type->tag",
		'text' => elgg_echo("file:type:$type->tag"),
		'href' =>  file_type_cloud_get_url($type, $friends),
	));
}
