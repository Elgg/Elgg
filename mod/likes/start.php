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

	// used to preload likes data before rendering river
	elgg_extend_view('page/components/list', 'likes/before_lists', 1);

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity_imprint', 'likes_setup_entity_imprint');

	elgg_register_plugin_hook_handler('permissions_check', 'annotation', 'likes_permissions_check');
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'likes_permissions_check_annotate', 0);


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

	return (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
}

/**
 * Add likes to entity menu at end of the menu
 */
function likes_entity_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggEntity)) {
		return $return;
	}

	$type = $entity->type;
	$subtype = $entity->getSubtype();
	$likable = (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
	if (!$likable) {
		return $return;
	}

	if (!$entity->canAnnotate(0, 'likes')) {
		return;
	}

	$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($entity->guid);

	// Always register both. That makes it super easy to toggle with javascript
	$return[] = ElggMenuItem::factory([
				'name' => 'likes',
				'parent_name' => 'actions',
				'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$entity->guid}"),
				'text' => elgg_echo('likes:like:add'),
				'title' => elgg_echo('likes:likethis'),
				'icon' => 'heart',
				'item_class' => $hasLiked ? 'hidden' : '',
				'priority' => 100,
				'deps' => ['elgg/likes'],
	]);
	$return[] = ElggMenuItem::factory([
				'name' => 'unlike',
				'parent_name' => 'actions',
				'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$entity->guid}"),
				'text' => elgg_echo('likes:like:remove'),
				'title' => elgg_echo('likes:remove'),
				'icon' => 'heart-o',
				'item_class' => $hasLiked ? '' : 'hidden',
				'priority' => 100,
				'deps' => ['elgg/likes'],
	]);

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

	$entity = $item->getObjectEntity();
	if (!$entity || !$entity->canAnnotate(0, 'likes')) {
		return;
	}

	$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($entity->guid);

	$return[] = ElggMenuItem::factory([
				'name' => 'likes',
				'parent_name' => 'actions',
				'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$entity->guid}"),
				'text' => elgg_echo('likes:like:add'),
				'title' => elgg_echo('likes:likethis'),
				'icon' => 'heart',
				'item_class' => $hasLiked ? 'hidden' : '',
				'priority' => 100,
				'deps' => ['elgg/likes'],
	]);
	$return[] = ElggMenuItem::factory([
				'name' => 'unlike',
				'parent_name' => 'actions',
				'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$entity->guid}"),
				'text' => elgg_echo('likes:like:remove'),
				'title' => elgg_echo('likes:remove'),
				'icon' => 'heart-o',
				'item_class' => $hasLiked ? '' : 'hidden',
				'priority' => 100,
				'deps' => ['elgg/likes'],
	]);

	$num_of_likes = \Elgg\Likes\DataService::instance()->getNumLikes($entity);

	$return[] = ElggMenuItem::factory([
				'name' => 'likes_count',
				'text' => elgg_format_element('span', [
					'class' => 'elgg-counter',
					'data-channel' => "likes:$entity->guid",
						], $num_of_likes),
				'title' => elgg_echo('likes:see'),
				'icon' => 'heart',
				'href' => '#',
				'item_class' => $num_of_likes ? '' : 'hidden',
				'link_class' => 'elgg-lightbox',
				'data-likes-guid' => $entity->guid,
				'data-colorbox-opts' => json_encode([
					'maxHeight' => '85%',
					'href' => elgg_normalize_url("ajax/view/likes/popup?guid=$entity->guid")
				]),
				'priority' => 100,
				'deps' => ['elgg/likes'],
	]);

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
	$params = ['entity' => $entity];
	$number = elgg_trigger_plugin_hook('likes:count', $type, $params, false);

	if ($number) {
		return $number;
	} else {
		return $entity->countAnnotations('likes');
	}
}

/**
 * Setup entity imprint
 *
 * @param \Elgg\Hook $hook Hook
 * @return ElggMenuItem[]
 */
function likes_setup_entity_imprint(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	if (!$entity) {
		return;
	}
	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	$likable = (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
	if (!$likable) {
		return;
	}

	$menu = $hook->getValue();


	$num_of_likes = \Elgg\Likes\DataService::instance()->getNumLikes($entity);

	$menu[] = ElggMenuItem::factory([
				'name' => 'likes_count',
				'text' => elgg_format_element('span', [
					'class' => 'elgg-counter',
					'data-channel' => "likes:$entity->guid",
						], $num_of_likes),
				'title' => elgg_echo('likes:see'),
				'icon' => 'heart',
				'href' => '#',
				'item_class' => $num_of_likes ? '' : 'hidden',
				'link_class' => 'elgg-lightbox',
				'data-likes-guid' => $entity->guid,
				'data-colorbox-opts' => json_encode([
					'maxHeight' => '85%',
					'href' => elgg_normalize_url("ajax/view/likes/popup?guid=$entity->guid")
				]),
				'priority' => 800,
				'deps' => ['elgg/likes'],
	]);

	return $menu;
}
