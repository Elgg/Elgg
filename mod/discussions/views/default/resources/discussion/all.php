<?php

elgg_push_collection_breadcrumbs('object', 'discussion');

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

echo elgg_view_page(elgg_echo('discussion:latest'), [
	'content' => elgg_view('discussion/listing/all'),
	'filter_value' => 'all',
]);
