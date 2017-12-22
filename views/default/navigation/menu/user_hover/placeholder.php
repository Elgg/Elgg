<?php

$menus_present = (array) elgg_get_config("elgg_lazy_hover_menus");

$user = elgg_extract("entity", $vars);
if (!$user instanceof ElggUser) {
	return;
}

$guid = (int) $user->getGUID();
$page_owner_guid = (int) elgg_get_page_owner_guid();
$contexts = elgg_get_context_stack();
$input = (array) elgg_get_config("input");

// generate MAC so we don't have to trust the client's choice of contexts
$data = serialize([$guid, $page_owner_guid, $contexts, $input]);
$mac = elgg_build_hmac($data)->getToken();

$attrs = [
	"rel" => $mac,
	"class" => "elgg-menu elgg-menu-hover",
	'data-menu-placeholder' => '1', // flag for the JS to know this menu isn't fully loaded yet
];

if (empty($menus_present[$mac])) {
	$attrs["data-elgg-menu-data"] = json_encode([
		"g" => $guid,
		"pog" => $page_owner_guid,
		"c" => $contexts,
		"m" => $mac,
		"i" => $input,
	]);

	$menus_present[$mac] = true;
	elgg_set_config("elgg_lazy_hover_menus", $menus_present);
}

echo elgg_format_element('ul', $attrs);
