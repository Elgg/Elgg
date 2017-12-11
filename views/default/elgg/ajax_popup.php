<?php

// capture global state necessary for menus
$state = [
	'contexts' => elgg_get_context_stack(),
	'input' => elgg_get_config("input"),
	'page_owner_guid' => elgg_get_page_owner_guid(),
];

// g = guid, d = ajax data, p = page_owner_guid, c = contexts, m = mac, i = input

$type = (string) get_input("t", '', false);

$ajax_data = (array) json_decode(get_input("d", '', false));

$page_owner_guid = (int) get_input("p", 0, false);

$contexts = (array) get_input("c", [], false);

$mac = get_input("m", "", false);

$input = (array) get_input("i", [], false);

// verify MAC
$serialized = serialize([$type, $ajax_data, $page_owner_guid, $contexts, $input]);

if (!elgg_build_hmac($serialized)->matchesToken($mac)) {
	return;
}

// render view using state as it was in the placeholder view
elgg_set_context_stack($contexts);
elgg_set_config("input", $input);
elgg_set_page_owner_guid($page_owner_guid);

echo elgg_view("elgg/ajax_popup/$type", [
	'ajax_data' => $ajax_data,
]);

// revert global state
elgg_set_context_stack($state['contexts']);
elgg_set_config("input", $state['input']);
elgg_set_page_owner_guid($state['page_owner_guid']);
