<?php
/**
 * Show all deleted items owned by the given user
 */

use Elgg\Exceptions\Http\PageNotFoundException;

if (!elgg_get_config('trash_enabled')) {
	throw new PageNotFoundException();
}

/* @var $user \ElggUser */
$user = elgg_get_page_owner_entity();

$title = elgg_echo('trash:owner:title');
if ($user->guid !== elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('trash:owner:title_owner', [$user->getDisplayName()]);
}

echo elgg_view_page($title, [
	'content' => elgg_view('trash/listing/owner', ['entity' => $user]),
	'filter_id' => 'trash',
	'show_owner_block' => false,
]);
