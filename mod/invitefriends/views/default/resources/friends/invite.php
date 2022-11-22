<?php
/**
 * Allow users to invite their friends to join the site
 */

echo elgg_view_page(elgg_echo('friends:invite'), [
	'content' => elgg_view_form('friends/invite', ['sticky_enabled' => true]),
	'show_owner_block_menu' => false,
	'filter_id' => 'friends',
	'filter_value' => 'invite',
]);
