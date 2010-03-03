<?php

	$page = get_input('page');
	
	if ($page = get_entity($page)) {
		
		if ($page->canEdit()) {
			
			// Bring all child elements forward
				$parent = $page->parent_guid;
				if ($children = elgg_get_entities_from_metadata(array('metadata_name' => 'parent_guid', 'metadata_value' => $page->getGUID()))) {
					foreach($children as $child) {
						$child->parent_guid = $parent;
					}
				}
				if ($page->delete()) {
					system_message(elgg_echo('pages:delete:success'));
					if ($parent) {
						if ($parent = get_entity($parent)) {
							forward($parent->getURL());
							exit;
						}
					}
					forward('pg/pages/owned/' . $_SESSION['user']->username);
					exit;
				}
			
		}
		
	}
	
	register_error(elgg_echo('pages:delete:failure'));
	forward($_SERVER['HTTP_REFERER']);

?>