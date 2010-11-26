<?php

	/**
	 * Elgg bookmarks delete action
	 * 
	 * @package ElggBookmarks
	 */

		$guid = get_input('bookmark_guid',0);
		if ($entity = get_entity($guid)) {

			$container = get_entity($entity->container_guid);
			if ($entity->canEdit()) {
				
				if ($entity->delete()) {
					
					system_message(elgg_echo("bookmarks:delete:success"));
					forward("pg/bookmarks/owner/$container->username/");
					
				}
				
			}
			
		}
		
		register_error(elgg_echo("bookmarks:delete:failed"));
		forward(REFERER);

?>