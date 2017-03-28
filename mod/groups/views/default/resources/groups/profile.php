<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_push_context('group_profile');
echo elgg_view_profile_page($group);
elgg_pop_context();