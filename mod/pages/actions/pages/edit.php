<?php
/**
 * Create or edit a page
 */

$variables = elgg()->fields->get('object', 'page');
$input = [];
foreach ($variables as $field) {
	$name = $field['name'];
	
	if ($name === 'title') {
		$input[$name] = elgg_get_title_input();
	} else {
		$input[$name] = get_input($name);
	}
	
	if ($field['#type'] === 'tags') {
		$input[$name] = elgg_string_to_array((string) $input[$name]);
	}

	if (elgg_extract('required', $field, false) && elgg_is_empty($input[$name])) {
		return elgg_error_response(elgg_echo('ValidationException'));
	}
}

// Get guids
$page_guid = (int) get_input('page_guid');
$container_guid = (int) get_input('container_guid');
$parent_guid = (int) get_input('parent_guid');

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

if (count($input) > 0) {
	// don't change access if not an owner/admin
	$can_change_access = true;

	if ($page->getOwnerEntity()) {
		$can_change_access = $page->getOwnerEntity()->canEdit();
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
	if ($tree_page instanceof ElggPage && ($page_guid === $tree_page->guid)) {
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
if ($page->guid !== $parent_guid) {
	// don't set parent to yourself
	$page->setParentByGUID($parent_guid);
}

if (!$page->save()) {
	return elgg_error_response(elgg_echo('pages:notsaved'));
}

if (get_input('header_remove')) {
	$page->deleteIcon('header');
} else {
	$page->saveIconFromUploadedFile('header', 'header');
}

// Now save description as an annotation
$page->annotate('page', $page->description ?? '', $page->access_id);

if ($new_page) {
	elgg_create_river_item([
		'action_type' => 'create',
		'object_guid' => $page->guid,
		'target_guid' => $page->container_guid,
	]);
}

return elgg_ok_response('', elgg_echo('pages:saved'), $page->getURL());
