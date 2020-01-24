<?php
/**
 * Returns content for the "online" page
 */

echo elgg_view_page(elgg_echo('members:title:online'), [
	'content' => get_online_users(),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'online',
]);
