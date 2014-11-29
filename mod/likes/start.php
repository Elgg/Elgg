<?php
/**
 * Likes plugin
 *
 */

elgg_register_event_handler('init', 'system', 'likes_init');

function likes_init() {

	elgg_extend_view('css/elgg', 'likes/css');
	elgg_extend_view('js/elgg', 'likes/js');

	// registered with priority < 500 so other plugins can remove likes
	elgg_register_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup', 400);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup', 400);

	$actions_base = elgg_get_plugins_path() . 'likes/actions/likes';
	elgg_register_action('likes/add', "$actions_base/add.php");
	elgg_register_action('likes/delete', "$actions_base/delete.php");
}

/**
 * Add likes to entity menu at end of the menu
 */
function likes_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];

	if ($entity->canAnnotate(0, 'likes')) {
		$hasLiked = elgg_annotation_exists($entity->guid, 'likes');

		// Always register both. That makes it super easy to toggle with javascript
		$return[] = ElggMenuItem::factory(array(
			'name' => 'like',
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
	if (elgg_is_logged_in()) {
		$item = $params['item'];

		// only like group creation #3958
		if ($item->type == "group" && $item->view != "river/group/create") {
			return $return;
		}

		// don't like users #4116
		if ($item->type == "user") {
			return $return;
		}

		$object = $item->getObjectEntity();
		if (!elgg_in_context('widgets') && $item->annotation_id == 0) {
			if ($object->canAnnotate(0, 'likes')) {
				$hasLiked = elgg_annotation_exists($object->guid, 'likes');

				// Always register both. That makes it super easy to toggle with javascript
				$return[] = ElggMenuItem::factory(array(
					'name' => 'like',
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
			}
		}
	}

	return $return;
}

/**
 * Count how many people have liked an entity.
 *
 * @param  ElggEntity $entity
 *
 * @return int Number of likes
 */
function likes_count($entity) {
	$type = $entity->getType();
	$params = array('entity' => $entity);
	$number = elgg_trigger_plugin_hook('likes:count', $type, $params, false);

	if ($number) {
		return $number;
	} else {
		return $entity->countAnnotations('likes');
	}
}
