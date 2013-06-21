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
	
	elgg_register_plugin_hook_handler('entity:url', 'object', 'elgg_comment_url_handler');

	elgg_register_entity_type('object', 'comment');
}

/**
 * Setup the menu shown with a comment
 *
 * @param string $hook   'register'
 * @param string $type   'menu:entity'
 * @param array  $return Array of ElggMenuItem objects
 * @param array  $params Array of view vars
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
	foreach ($return as $key => $item) {
		if (in_array($item->getName(), array('access', 'edit'))) {
			unset($return[$key]);
		}
	}

	return $return;
}

/**
 * Format and return the URL for a comment.
 *
 * This is the container's URL because comments don't have their own page.
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $return URL for entity
 * @param array  $params Array with the elgg entity passed in as 'entity'
 *
 * @return string
 */
function elgg_comment_url_handler($hook, $type, $return, $params) {
	$entity = $params['entity'];
	/* @var ElggObject $entity */

	if (!elgg_instanceof($entity, 'object', 'comment') || !$entity->getOwnerEntity()) {
		// not a comment or has no owner

		// @todo handle anonymous comments
		return $return;
	}

	return $entity->getContainerEntity()->getURL();
}

/**
 * Allow users to comment entities not owned by them.
 * 
 * Object being commented is used as the container of the comment so
 * permission check must be overridden if user isn't the owner of the object.
 * 
 * @param string  $hook   'container_permissions_check'
 * @param string  $type   'object'
 * @param boolean $return True if not already changed by an other hook handler
 * @param array   $params Array of parameters (container, user, subtype)
 * 
 * @return array
 */
function comments_container_permissions_override ($hook, $type, $return, $params) {
	if ($params['subtype'] === 'comment') {
		return true;
	}

	return $return;
}

elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'comments_container_permissions_override');

elgg_register_event_handler('init', 'system', 'comments_init', 0);
