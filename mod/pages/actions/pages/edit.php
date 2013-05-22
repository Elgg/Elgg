<?php
/**
 * Create or edit a page
 *
 * @package ElggPages
 */

$variables = elgg_get_config('pages');
$input = array();
foreach ($variables as $name => $type) {
	if ($name == 'title') {
		$input[$name] = htmlspecialchars(get_input($name, '', false), ENT_QUOTES, 'UTF-8');
	} else {
		$input[$name] = get_input($name);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$page_guid = (int)get_input('page_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('page');

if (!$input['title']) {
	register_error(elgg_echo('pages:error:no_title'));
	forward(REFERER);
}

if ($page_guid) {
	$page = get_entity($page_guid);
	if (!pages_is_page($page) || !$page->canEdit()) {
		register_error(elgg_echo('pages:cantedit'));
		forward(REFERER);
	}
	$new_page = false;
} else {
	$page = new ElggObject();
	if ($parent_guid) {
		$page->subtype = 'page';
	} else {
		$page->subtype = 'page_top';
	}
	$new_page = true;
}

if (sizeof($input) > 0) {
	// don't change access if not an owner/admin
	$user = elgg_get_logged_in_user_entity();
	$can_change_access = true;

	if ($user && $page) {
		$can_change_access = $user->isAdmin() || $user->getGUID() == $page->owner_guid;
	}
	
	foreach ($input as $name => $value) {
		if (($name == 'access_id' || $name == 'write_access_id') && !$can_change_access) {
			continue;
		}
		if ($name == 'parent_guid') {
			continue;
		}

		$page->$name = $value;
	}
}

// need to add check to make sure user can write to container
$page->container_guid = $container_guid;

if ($parent_guid && $parent_guid != $page_guid) {
	// Check if parent isn't below the page in the tree
	if ($page_guid) {
		$tree_page = get_entity($parent_guid);
		while ($tree_page->parent_guid > 0 && $page_guid != $tree_page->guid) {
			$tree_page = get_entity($tree_page->parent_guid);
		}
		// If is below, bring all child elements forward
		if ($page_guid == $tree_page->guid) {
			$previous_parent = $page->parent_guid;
			$children = elgg_get_entities_from_metadata(array(
				'metadata_name' => 'parent_guid',
				'metadata_value' => $page->getGUID()
			));
			if ($children) {
				foreach ($children as $child) {
					$child->parent_guid = $previous_parent;
				}
			}
		}
	}
	$page->parent_guid = $parent_guid;
}

if ($page->save()) {

	elgg_clear_sticky_form('page');

	// Now save description as an annotation
	$page->annotate('page', $page->description, $page->access_id);

	system_message(elgg_echo('pages:saved'));

	if ($new_page) {
		elgg_create_river_item(array(
			'view' => 'river/object/page/create',
			'action_type' => 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $page->guid,
		));
	}

	forward($page->getURL());
} else {
	register_error(elgg_echo('pages:notsaved'));
	forward(REFERER);
}
