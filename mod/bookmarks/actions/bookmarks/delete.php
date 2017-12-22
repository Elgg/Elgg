<?php
/**
 * Delete a bookmark
 *
 * @package Bookmarks
 */

$guid = (int) get_input('guid');
$bookmark = get_entity($guid);

if (!$bookmark instanceof ElggBookmark || !$bookmark->canDelete()) {
	return elgg_error_response(elgg_echo('bookmarks:delete:failed'));
}

$container = $bookmark->getContainerEntity();
if (!$bookmark->delete()) {
	return elgg_error_response(elgg_echo('bookmarks:delete:failed'));
}

if ($container instanceof \ElggGroup) {
	$forward_url = "bookmarks/group/{$container->guid}/all";
} else {
	$forward_url = "bookmarks/owner/{$container->username}";
}

return elgg_ok_response('', elgg_echo('bookmarks:delete:success'), $forward_url);

