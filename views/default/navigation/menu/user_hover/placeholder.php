<?php

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$guid = $user->guid;
$page_owner_guid = elgg_get_page_owner_guid();
$contexts = elgg_get_context_stack();
$input = (array) elgg_get_config('input');

// generate MAC so we don't have to trust the client's choice of contexts
$data = serialize([$guid, $page_owner_guid, $contexts, $input]);
$mac = elgg_build_hmac($data)->getToken();

echo elgg_format_element('ul', [
	'class' => ['elgg-menu', 'elgg-menu-hover'],
	'data-menu-id' => $mac,
	'data-elgg-menu-data' => json_encode([
		'g' => $guid,
		'pog' => $page_owner_guid,
		'c' => $contexts,
		'm' => $mac,
		'i' => $input,
	]),
]);
