<?php
/**
 * Delete a bookmark
 *
 * @package Bookmarks
 */

$guid = get_input('guid');
$bookmark = get_entity($guid);

if (elgg_instanceof($bookmark, 'object', 'bookmarks') && $bookmark->canEdit() && $bookmark->delete()) {
	system_message(elgg_echo("bookmarks:delete:success"));
	forward(REFERER);
} else {
	register_error(elgg_echo("bookmarks:delete:failed"));
	forward(REFERER);
}