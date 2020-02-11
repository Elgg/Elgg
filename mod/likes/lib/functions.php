<?php
use Elgg\Likes\DataService;

/**
 * Holds helper functions for likes plugin
 */

/**
 * Get thumbs up menu item
 *
 * @param ElggEntity $entity   Entity
 * @param int        $priority Item priority
 *
 * @return ElggMenuItem
 * @internal
 */
function _likes_menu_item(ElggEntity $entity, $priority = 500) {
	$is_liked = DataService::instance()->currentUserLikesEntity($entity->guid);

	return \ElggMenuItem::factory([
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
 * @internal
 */
function _likes_count_menu_item(ElggEntity $entity, $priority = 500) {
	$num_likes = DataService::instance()->getNumLikes($entity);

	if ($num_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis', [$num_likes]);
	} else {
		$likes_string = elgg_echo('likes:userslikedthis', [$num_likes]);
	}

	return \ElggMenuItem::factory([
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
