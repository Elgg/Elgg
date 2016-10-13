<?php
/**
 * Likes plugin
 *
 * To make your content likable, use the likes:is_likable hook to register your type:subtype. E.g.
 *
 * <code>
 * elgg_register_plugin_hook_handler('likes:is_likable', 'object:mysubtype', 'Elgg\Values::getTrue');
 * </code>
 */

elgg_register_event_handler('init', 'system', 'likes_init');

function likes_init() {

	elgg_extend_view('elgg.css', 'likes/css');

	// inline module
	elgg_extend_view('elgg.js', 'elgg/likes.js');
	elgg_require_js('elgg/likes');

	// used to preload likes data before rendering river
	elgg_extend_view('page/components/list', 'likes/before_lists', 1);

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup', 400);
	elgg_register_plugin_hook_handler('permissions_check', 'annotation', 'likes_permissions_check');
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'likes_permissions_check_annotate', 0);
	
	$actions_base = __DIR__ . '/actions/likes';
	elgg_register_action('likes/add', "$actions_base/add.php");
	elgg_register_action('likes/delete', "$actions_base/delete.php");
	
	elgg_register_ajax_view('likes/popup');
}

/**
 * Only allow annotation owner (or someone who can edit the owner, like an admin) to delete like
 *
 * @param string $hook   "permissions_check"
 * @param string $type   "annotation"
 * @param array  $return Current value
 * @param array  $params Hook parameters
 *
 * @return bool
 */
function likes_permissions_check($hook, $type, $return, $params) {
	
	$annotation = elgg_extract('annotation', $params);
	if (!$annotation || $annotation->name !== 'likes') {
		return $return;
	}
	
	$owner = $annotation->getOwnerEntity();
	if (!$owner) {
		return $return;
	}
	
	return $owner->canEdit();
}

/**
 * Sets the default for whether to allow liking/viewing likes on an entity
 *
 * @param string $hook   "permissions_check:annotate"
 * @param string $type   "object"|"user"|"group"|"site"
 * @param array  $return Current value
 * @param array  $params Hook parameters
 *
 * @return bool
 */
function likes_permissions_check_annotate($hook, $type, $return, $params) {
	if (elgg_extract('annotation_name', $params) !== 'likes') {
		return;
	}

	$user = elgg_extract('user', $params);
	$entity = elgg_extract('entity', $params);

	if (!$user || !$entity instanceof ElggEntity) {
		return false;
	}

	$type = $entity->type;
	$subtype = $entity->getSubtype();

	return (bool)elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
}

/**
 * Add likes to entity menu at end of the menu
 */
function likes_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggEntity)) {
		return $return;
	}

	$type = $entity->type;
	$subtype = $entity->getSubtype();
	$likable = (bool)elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
	if (!$likable) {
		return $return;
	}

	if ($entity->canAnnotate(0, 'likes')) {
		$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($entity->guid);
		
		// Always register both. That makes it super easy to toggle with javascript
		$return[] = ElggMenuItem::factory(array(
			'name' => 'likes',
			'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$entity->guid}"),
			'text' => elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('likes:likethis'),
			'item_class' => $hasLiked ? 'hidden' : '',
			'priority' => 1000,
		));
		$return[] = ElggMenuItem::factory(array(
			'name' => 'unlike',
			'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$entity->guid}"),
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('likes:remove'),
			'item_class' => $hasLiked ? '' : 'hidden',
			'priority' => 1000,
		));
	}
	
	// likes count
	$count = elgg_view('likes/count', array('entity' => $entity));
	if ($count) {
		$options = array(
			'name' => 'likes_count',
			'text' => $count,
			'href' => false,
			'priority' => 1001,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Add a like button to river actions
 */
function likes_river_menu_setup($hook, $type, $return, $params) {
	if (!elgg_is_logged_in() || elgg_in_context('widgets')) {
		return;
	}

	$item = $params['item'];
	/* @var ElggRiverItem $item */

	// only like group creation #3958
	if ($item->type == "group" && $item->view != "river/group/create") {
		return;
	}

	if ($item->annotation_id != 0) {
		return;
	}

	$object = $item->getObjectEntity();
	if (!$object || !$object->canAnnotate(0, 'likes')) {
		return;
	}

	$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($object->guid);

	// Always register both. That makes it super easy to toggle with javascript
	$return[] = ElggMenuItem::factory(array(
		'name' => 'likes',
		'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$object->guid}"),
		'text' => elgg_view_icon('thumbs-up'),
		'title' => elgg_echo('likes:likethis'),
		'item_class' => $hasLiked ? 'hidden' : '',
		'priority' => 100,
	));
	$return[] = ElggMenuItem::factory(array(
		'name' => 'unlike',
		'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$object->guid}"),
		'text' => elgg_view_icon('thumbs-up-alt'),
		'title' => elgg_echo('likes:remove'),
		'item_class' => $hasLiked ? '' : 'hidden',
		'priority' => 100,
	));

	// likes count
	$count = elgg_view('likes/count', array('entity' => $object));
	if ($count) {
		$return[] = ElggMenuItem::factory(array(
			'name' => 'likes_count',
			'text' => $count,
			'href' => false,
			'priority' => 101,
		));
	}

	return $return;
}

/**
 * Count how many people have liked an entity.
 *
 * @param ElggEntity $entity
 *
 * @return int Number of likes
 */
function likes_count(ElggEntity $entity) {
	$type = $entity->getType();
	$params = array('entity' => $entity);
	$number = elgg_trigger_plugin_hook('likes:count', $type, $params, false);

	if ($number) {
		return $number;
	} else {
		return $entity->countAnnotations('likes');
	}
}
