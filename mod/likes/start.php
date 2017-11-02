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

/**
 * Likes init
 *
 * @return void
 */
function likes_init() {
	elgg_extend_view('elgg.css', 'elgg/likes.css');

	// used to preload likes data before rendering river
	elgg_extend_view('page/components/list', 'likes/before_lists', 1);

	elgg_register_plugin_hook_handler('register', 'menu:social', 'likes_social_menu_setup');
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
 * @param bool   $return Current value
 * @param array  $params Hook parameters
 *
 * @return void|bool
 */
function likes_permissions_check($hook, $type, $return, $params) {
	$annotation = elgg_extract('annotation', $params);
	if (!$annotation || $annotation->name !== 'likes') {
		return;
	}
	
	$owner = $annotation->getOwnerEntity();
	if (!$owner) {
		return;
	}
	
	return $owner->canEdit();
}

/**
 * Sets the default for whether to allow liking/viewing likes on an entity
 *
 * @param string $hook   "permissions_check:annotate"
 * @param string $type   "object"|"user"|"group"|"site"
 * @param bool   $return Current value
 * @param array  $params Hook parameters
 *
 * @return void|bool
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
 * Add likes to social menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:social'
 *
 * @return void|ElggMenuItem[]
 *
 * @since 3.0
 */
function likes_social_menu_setup(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity) {
		return;
	}
	
	$type = $entity->type;
	$subtype = $entity->getSubtype();

	$supports_likes = (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
	if (!$supports_likes) {
		return;
	}
	
	$return = $hook->getValue();

	if ($entity->canAnnotate(0, 'likes')) {
		$return[] = _likes_menu_item($entity);
	}
	
	$return[] = _likes_count_menu_item($entity);

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
function _likes_menu_item(ElggEntity $entity, $priority = 500) {
	$is_liked = DataService::instance()->currentUserLikesEntity($entity->guid);

	return ElggMenuItem::factory([
		'name' => 'likes',
		'href' => '#',
		'icon' => 'thumbs-up',
		'class' => $is_liked ? 'elgg-state-active' : '',
		'text' => elgg_echo($is_liked ? 'likes:remove' : 'likes:likethis'),
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
function _likes_count_menu_item(ElggEntity $entity, $priority = 500) {
	$num_likes = DataService::instance()->getNumLikes($entity);

	if ($num_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis', [$num_likes]);
	} else {
		$likes_string = elgg_echo('likes:userslikedthis', [$num_likes]);
	}

	return ElggMenuItem::factory([
		'name' => 'likes_count',
		'text' => '',
		'title' => elgg_echo('likes:see'),
		'href' => '#',
		'badge' => $likes_string,
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
 * Get the count of how many people have liked an entity
 *
 * @param ElggEntity $entity entity to get likes count for
 *
 * @return int
 */
function likes_count(ElggEntity $entity) {
	$type = $entity->getType();
	$params = ['entity' => $entity];
	$number = elgg_trigger_plugin_hook('likes:count', $type, $params, false);

	if ($number !== false) {
		return (int) $number;
	}
	
	return $entity->countAnnotations('likes');
}

return function() {
	elgg_register_event_handler('init', 'system', 'likes_init');
};
