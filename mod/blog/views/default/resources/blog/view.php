<?php

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'blog');

$blog = get_entity($guid);

echo elgg_view_profile_page($blog, [], [
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => 'view',
	]),
]);
