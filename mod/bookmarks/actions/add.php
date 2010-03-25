<?php

	/**
	 * Elgg bookmarks add/save action
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
	
	gatekeeper();

		$title = strip_tags(get_input('title'));
		$guid = get_input('bookmark_guid',0);
		$description = get_input('description');
		$address = get_input('address');
		$access = get_input('access');
		$shares = get_input('shares',array());
		
		$tags = get_input('tags');
		$tagarray = string_to_tag_array($tags);
		
		if ($guid == 0) {
			
			$entity = new ElggObject;
			$entity->subtype = "bookmarks";
			$entity->owner_guid = $_SESSION['user']->getGUID();
			$entity->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
			
		} else {
			
			$canedit = false;
			if ($entity = get_entity($guid)) {
				if ($entity->canEdit()) {
					$canedit = true;
				}
			}
			if (!$canedit) {
				system_message(elgg_echo('notfound'));
				forward("pg/bookmarks");
			}
			
		}
		
		$entity->title = $title;
		$entity->address = $address;
		$entity->description = $description;
		$entity->access_id = $access;
		$entity->tags = $tagarray;
		
		if ($entity->save()) {
			$entity->clearRelationships();
			$entity->shares = $shares;
		
			if (is_array($shares) && sizeof($shares) > 0) {
				foreach($shares as $share) {
					$share = (int) $share;
					add_entity_relationship($entity->getGUID(),'share',$share);
				}
			}
			system_message(elgg_echo('bookmarks:save:success'));
			//add to river
			add_to_river('river/object/bookmarks/create','create',$_SESSION['user']->guid,$entity->guid);
			forward($entity->getURL());
		} else {
			register_error(elgg_echo('bookmarks:save:failed'));
			forward("pg/bookmarks");
		}

?>
