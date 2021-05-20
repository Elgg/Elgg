<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

$content = elgg_view_layout('widgets', [
	'num_columns' => 3,
	'show_access' => false,
	'no_widgets' => function () {
		echo elgg_view('dashboard/blurb');
	},
]);

echo elgg_view_page(elgg_echo('dashboard'), [
	'title' => false,
	'sidebar' => false,
	'content' => $content,
	'header' => false,
]);
