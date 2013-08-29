<?php

$user = get_user(get_input("guid"));
$page_owner_guid = (int) get_input("page_owner_guid");
$contexts = get_input("context");

if ($user) {
	if ($page_owner_guid) {
		// set correct page_owner
		elgg_set_page_owner_guid($page_owner_guid);
	}
	
	if(!empty($contexts)) {
		// set correct contexts
		foreach ($contexts as $context) {
			elgg_push_context($context);
		}
	}
	
	$params = array(
		'entity' => $user,
		'username' => $user->username,
		'name' => $user->name
	);
	
	echo elgg_view_menu('user_hover', $params);
	
	if(!empty($contexts)) {
		// revert extra contexts
		$contexts = array_reverse($contexts);
		foreach ($contexts as $context) {
			elgg_pop_context($context);
		}
	}
}