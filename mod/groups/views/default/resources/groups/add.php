<?php

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));

elgg_require_js('elgg/groups/edit');

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

$content = elgg_view('groups/edit');

echo elgg_view_page(elgg_echo('groups:add'), [
	'content' => $content,
	'filter_id' => 'groups/edit',
]);
