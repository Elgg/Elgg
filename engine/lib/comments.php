<?php
/**
 * Elgg comments
 *
 * @package    Elgg.Core
 * @subpackage Comments
 * @since 1.9
 */

/**
 * Comments initialisation function
 *
 * @return void
 * @access private
 */
function comments_init() {
	// Register entity type
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elgg_comment_setup_entity_menu', 900);
	
	elgg_register_entity_url_handler('object', 'comment', 'comments_url_handler');
}

/**
 * Setup the menu shown with a comment
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * 
 * @return array
 */
function elgg_comment_setup_entity_menu ($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'object', 'comment')) {
		return $return;
	}

	// Remove edit link and access level from the menu
	foreach($return as $key => $item) {
		if (in_array($item->getName(), array('access', 'edit'))) {
			unset($return[$key]);
		}
	}

	return $return;
}

/**
 * Allow users to comment entities not owned by them.
 * 
 * Object being commented is used as the container of the comment so
 * permission check must be overridden if user isn't the owner of the object.
 * 
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * 
 * @return array
 */
function comments_container_permissions_override ($hook, $type, $return, $params) {
	if ($params['subtype'] === 'comment') {
		return true;
	}

	return $return;
}

/**
 * Format and return the URL for comments.
 * 
 * The url consists of container's url and comment's anchor.
 *
 * @param ElggObject $entity Comment object
 * @return string URL of comment.
 */
function comments_url_handler($entity) {
	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}
	
	$container_url = $entity->getContainerEntity()->getURL();
	
	$comment_anchor = "comment-{$entity->getGUID()}";

	return "$container_url#$comment_anchor";
}

elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'comments_container_permissions_override');

elgg_register_event_handler('init', 'system', 'comments_init', 0);
