<?php
/**
 * Create or edit a page
 */

$variables = elgg_get_config('pages');
$input = [];
foreach ($variables as $name => $type) {
	if ($name == 'title') {
		$input[$name] = elgg_get_title_input();
	} else {
		$input[$name] = get_input($name);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$page_guid = (int) get_input('page_guid');
$container_guid = (int) get_input('container_guid');
$parent_guid = (int) get_input('parent_guid');

elgg_make_sticky_form('page');

if (!$input['title']) {
	return elgg_error_response(elgg_echo('pages:error:no_title'));
}

if ($page_guid) {
	$page = get_entity($page_guid);
	if (!$page instanceof ElggPage || !$page->canEdit()) {
		return elgg_error_response(elgg_echo('pages:cantedit'));
	}
	$new_page = false;
} else {
	$page = new ElggPage();
	$page->container_guid = $container_guid;
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

if (!$new_page && $parent_guid && $parent_guid !== $page_guid) {
	// Check if parent isn't below the page in the tree
	$tree_page = get_entity($parent_guid);
	while ($tree_page instanceof ElggPage && $page_guid !== $tree_page->guid) {
		$tree_page = $tree_page->getParentEntity();
	}
	// If is below, bring all child elements forward
	if ($page_guid === $tree_page->guid) {
		$previous_parent = $page->getParentGUID();

		$children = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'page',
			'metadata_name_value_pairs' => [
				'parent_guid' => $page->guid,
			],
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		
		/* @var $child ElggPage */
		foreach ($children as $child) {
			$child->setParentByGUID($previous_parent);
		}
	}
}

// set parent
$page->setParentByGUID($parent_guid);

if (!$page->save()) {
	return elgg_error_response(elgg_echo('pages:notsaved'));
}

elgg_clear_sticky_form('page');

// Now save description as an annotation
$page->annotate('page', $page->description, $page->access_id);

if ($new_page) {
	elgg_create_river_item([
		'action_type' => 'create',
		'object_guid' => $page->guid,
	]);
}

return elgg_ok_response('', elgg_echo('pages:saved'), $page->getURL());
