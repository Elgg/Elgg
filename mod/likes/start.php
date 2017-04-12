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

use Elgg\Likes\DataService;
use Elgg\Services\AjaxResponse;
use Elgg\Likes\AjaxResponseHandler;
use Elgg\Likes\JsConfigHandler;

elgg_register_event_handler('init', 'system', 'likes_init');

function likes_init() {
	elgg_extend_view('elgg.css', 'elgg/likes.css');

	// used to preload likes data before rendering river
	elgg_extend_view('page/components/list', 'likes/before_lists', 1);

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup', 400);
	elgg_register_plugin_hook_handler('permissions_check', 'annotation', 'likes_permissions_check');
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'all', 'likes_permissions_check_annotate', 0);

	// update count when an entity is subject of an ajax request
	elgg_register_plugin_hook_handler(AjaxResponse::RESPONSE_HOOK, 'all', AjaxResponseHandler::class);

	// pass config to elgg/likes module
	elgg_register_plugin_hook_handler('elgg.data', 'site', JsConfigHandler::class);
		
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
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggEntity)) {
		return $return;
	}

	if ($entity->canAnnotate(0, 'likes')) {
		$return[] = _likes_menu_item($entity, 1000);
	}
	
	$return[] = _likes_count_menu_item($entity, 1001);

	return $return;
}

/**
 * Add a like button to river actions
 */
function likes_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
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
	if (!$entity) {
		return;
	}

	if ($entity->canAnnotate(0, 'likes')) {
		$return[] = _likes_menu_item($entity, 100);
	}

	$return[] = _likes_count_menu_item($entity, 101);

	return $return;
}

/**
 * Get thumbs up menu item
 *
 * @param ElggEntity $entity   Entity
 * @param int        $priority Item priority
 *
 * @return ElggMenuItem
 * @access private
 */
function _likes_menu_item(ElggEntity $entity, $priority) {
	$is_liked = DataService::instance()->currentUserLikesEntity($entity->guid);

	return ElggMenuItem::factory([
		'name' => 'likes',
		'href' => '#',
		'text' => elgg_view_icon('thumbs-up', [
			'class' => $is_liked ? 'elgg-state-active' : '',
		]),
		'title' => elgg_echo($is_liked ? 'likes:remove' : 'likes:likethis'),
		'data-likes-state' => $is_liked ? 'liked' : 'unliked',
		'data-likes-guid' => $entity->guid,
		'priority' => $priority,
		'deps' => ['elgg/likes'],
	]);
}

/**
 * Get likes count menu item.
 *
 * @param ElggEntity $entity   Entity
 * @param int        $priority Item priority
 *
 * @return ElggMenuItem
 * @access private
 */
function _likes_count_menu_item(ElggEntity $entity, $priority) {
	$num_likes = DataService::instance()->getNumLikes($entity);

	if ($num_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis', [$num_likes]);
	} else {
		$likes_string = elgg_echo('likes:userslikedthis', [$num_likes]);
	}

	return ElggMenuItem::factory([
		'name' => 'likes_count',
		'text' => $likes_string,
		'title' => elgg_echo('likes:see'),
		'href' => '#',
		'data-likes-guid' => $entity->guid,
		'data-colorbox-opts' => json_encode([
			'maxHeight' => '85%',
			'href' => elgg_normalize_url("ajax/view/likes/popup?guid={$entity->guid}")
		]),
		'link_class' => 'elgg-lightbox',
		'item_class' => $num_likes ? '' : 'hidden',
		'priority' => $priority,
		'deps' => ['elgg/likes'],
	]);
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
