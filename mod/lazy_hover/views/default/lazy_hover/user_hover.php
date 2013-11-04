<?php

// g = guid, pog = page_owner_guid, c = contexts, m = mac
$guid = (int)get_input("g", 0, false);
$page_owner_guid = (int)get_input("pog", 0, false);
$contexts = (array)get_input("c", array(), false);
$mac = get_input('m', '', false);

// verify MAC
$data = serialize(array($guid, $page_owner_guid, $contexts));
if ($mac !== hash_hmac('sha256', $data, get_site_secret())) {
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
foreach ($contexts as $context) {
	elgg_push_context($context);
}

$params = array(
	'entity' => $user,
	'username' => $user->username,
	'name' => $user->name
);

echo elgg_view_menu('user_hover', $params);

// revert extra contexts
foreach ($contexts as $context) {
	elgg_pop_context();
}
