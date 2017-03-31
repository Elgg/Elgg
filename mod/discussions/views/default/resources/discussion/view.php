<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

echo elgg_view_profile_page($topic, [
	'sidebar' => elgg_view('discussion/sidebar', $vars),
]);
