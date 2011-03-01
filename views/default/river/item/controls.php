<?php
/**
 * Controls on an river item
 *
 *
 * @uses $vars['item']
 */

$object = $vars['item']->getObjectEntity();

if (elgg_is_logged_in()) {
	// comments and non-objects cannot be commented on or liked
	if ($vars['item']->annotation_id == 0) {
		// comments
		if ($object->canComment()) {
			elgg_register_menu_item('river', array(
				'name' => 'comment',
				'href' => "#comments-add-$object->guid",
				'text' => elgg_echo('generic_comments:text'),
				'class' => "elgg-toggler",
			));
		}

		// like this
		if ($object->canAnnotate(0, 'likes')) {
			if (!elgg_annotation_exists($object->getGUID(), 'likes')) {
				
				elgg_register_menu_item('river', array(
					'name' => 'likes',
					'href' => "action/likes/add?guid={$object->getGUID()}",
					'text' => elgg_echo('likes:likethis'),
					'is_action' => true,
				));
			} else {
				$likes = elgg_get_annotations(array(
					'guid' => $guid,
					'annotation_name' => 'likes',
					'owner_guid' => elgg_get_logged_in_user_guid()
				));

				elgg_register_menu_item('river', array(
					'href' => "action/likes/delete?annotation_id={$likes[0]->id}",
					'text' => elgg_echo('likes:remove'),
					'is_action' => true,
				));
			}
		}
	}

	echo elgg_view_menu('river', array('sort_by' => 'priority', 'item' => $vars['item']));
}