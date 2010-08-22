<?php
/**
 * Elgg bookmarks delete action
 *
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$guid = get_input('bookmark_guid',0);
if ($entity = get_entity($guid)) {
	if ($entity->canEdit()) {
		$container = get_entity($entity->container_guid);
		if ($entity->delete()) {
			system_message(elgg_echo("bookmarks:delete:success"));
			forward("pg/bookmarks/$container->username/");
		}
	}
}

register_error(elgg_echo("bookmarks:delete:failed"));
forward($_SERVER['HTTP_REFERER']);