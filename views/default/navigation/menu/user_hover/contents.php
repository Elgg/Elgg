<?php

// capture global state necessary for menus
$state = [
	'contexts' => elgg_get_context_stack(),
	'input' => elgg_get_config("input"),
	'page_owner_guid' => elgg_get_page_owner_guid(),
];

// g = guid, pog = page_owner_guid, c = contexts, m = mac
$guid = (int) get_input("g", 0, false);
$page_owner_guid = (int) get_input("pog", 0, false);
$contexts = (array) get_input("c", [], false);
$mac = get_input("m", "", false);
$input = (array) get_input("i", [], false);

// verify MAC
$data = serialize([$guid, $page_owner_guid, $contexts, $input]);

if (!elgg_build_hmac($data)->matchesToken($mac)) {
	return;
}

$user = get_user($guid);
if (!$user) {
	return;
}

// render view using state as it was in the placeholder view
elgg_set_context_stack($contexts);
elgg_set_config("input", $input);
elgg_set_page_owner_guid($page_owner_guid);

$menu = elgg_view_menu('user_hover', [
	'entity' => $user,
	'username' => $user->username,
	'name' => $user->getDisplayName(),
]);

// wrapping in a li as it is inject into a ul via javascript
echo elgg_format_element('li', [], $menu);

// revert global state
elgg_set_context_stack($state['contexts']);
elgg_set_config("input", $state['input']);
elgg_set_page_owner_guid($state['page_owner_guid']);
