<?php

$menus_present = (array)elgg_get_config('lazy_hover:menus');

$user = elgg_extract("entity", $vars);
if (!elgg_instanceof($user, 'user')) {
	return;
}

$guid = (int)$user->guid;
$page_owner_guid = (int)elgg_get_page_owner_guid();
$contexts = (array)elgg_get_config("context");

// generate MAC so we don't have to trust the client's choice of contexts
$data = serialize(array($guid, $page_owner_guid, $contexts));
$mac = hash_hmac('sha256', $data, get_site_secret());

$attrs = array(
	'rel' => $mac,
	'class' => "elgg-menu elgg-menu-hover elgg-ajax-loader pvl",
);

if (empty($menus_present[$mac])) {
	$attrs['data-json'] = json_encode(array(
		'g' => $guid,
		'pog' => $page_owner_guid,
		'c' => $contexts,
		'm' => $mac,
	));

	$menus_present[$mac] = true;
	elgg_set_config('lazy_hover:menus', $menus_present);
}

echo "<ul " . elgg_format_attributes($attrs) . '></ul>';

