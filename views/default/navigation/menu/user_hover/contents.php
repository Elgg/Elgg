<?php

// g = guid, pog = page_owner_guid, c = contexts, m = mac
$guid = (int) get_input("g", 0, false);
$page_owner_guid = (int) get_input("pog", 0, false);
$contexts = (array) get_input("c", array(), false);
$mac = get_input("m", "", false);
$input = (array) get_input("i", array(), false);

// verify MAC
$data = array($guid, $page_owner_guid, $contexts, $input);

if (!elgg_build_hmac($data)->matchesToken($mac)) {
	return;
}

$user = get_user($guid);
if (!$user) {
	return;
}

if ($page_owner_guid) {
	// set correct page_owner
	elgg_set_page_owner_guid($page_owner_guid);
}

// set correct contexts
if (!empty($contexts)) {
	elgg_set_context_stack($contexts);
}

elgg_set_config("input", $input);

$params = array(
	"entity" => $user,
	"username" => $user->username,
	"name" => $user->name
);

echo elgg_view_menu("user_hover", $params);

// revert extra contexts
foreach ($contexts as $context) {
	elgg_pop_context();
}
