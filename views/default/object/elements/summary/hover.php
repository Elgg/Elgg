<?php

$entity = elgg_extract('entity', $vars);

$guid = (int) $entity->getGUID();
$page_owner_guid = (int) elgg_get_page_owner_guid();
$contexts = elgg_get_context_stack();
$input = (array) elgg_get_config("input");

// generate MAC so we don't have to trust the client's choice of contexts
$data = serialize([$guid, $page_owner_guid, $contexts, $input]);
$mac = elgg_build_hmac($data)->getToken();

echo elgg_view('output/url', [
	'text' => elgg_echo('more'),
	'icon' => 'ellipsis-v',
	'rel' => 'popup',
	'data-ajax-href' => 'ajax/view/navigation/menu/entity/contents',
	'data-ajax-target' => json_encode([
		'#tag_name' => 'ul',
		'class' => 'elgg-menu elgg-menu-hover',
	]),
	'data-ajax-reload' => false,
	'data-ajax-query' => json_encode([
		"g" => $guid,
		"pog" => $page_owner_guid,
		"c" => $contexts,
		"m" => $mac,
		"i" => $input,
	]),
]);
