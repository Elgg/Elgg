<?php
/**
 * Elgg comments library
 *
 * @package    Elgg.Core
 * @subpackage Comments
 * @since 1.9
 */

elgg_register_event_handler('init', 'system', '_elgg_comments_init');

/**
 * Comments initialization function
 *
 * @return void
 * @access private
 */
function _elgg_comments_init() {
	elgg_register_entity_type('object', 'comment');
	elgg_register_plugin_hook_handler('register', 'menu:entity', '_elgg_comment_setup_entity_menu', 900);
	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_comment_url_handler');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '_elgg_comments_container_permissions_override');
	elgg_register_ajax_view('core/ajax/edit_comment');
}

/**
 * Setup the menu shown with a comment
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:entity'
 * @param ElggMenuItem[] $return Array of ElggMenuItem objects
 * @param array          $params Array of view vars
 *
 * @return array
 * @access private
 */
function _elgg_comment_setup_entity_menu($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'object', 'comment')) {
		return $return;
	}

	// Remove edit link and access level from the menu
	foreach ($return as $key => $item) {
		if ($item->getName() === 'access') {
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
 * @access private
 */
function _elgg_comment_url_handler($hook, $type, $return, $params) {
	$entity = $params['entity'];
	/* @var ElggObject $entity */

	if (!elgg_instanceof($entity, 'object', 'comment') || !$entity->getOwnerEntity()) {
		// not a comment or has no owner

		// @todo handle anonymous comments
		return $return;
	}

	$container = $entity->getContainerEntity();
	if (!$container) {
		return $return;
	}

	return $container->getURL();
}

/**
 * Allow users to comment on entities not owned by them.
 *
 * Object being commented on is used as the container of the comment so
 * permission check must be overridden if user isn't the owner of the object.
 *
 * @param string  $hook   'container_permissions_check'
 * @param string  $type   'object'
 * @param boolean $return Can the current user write to this container?
 * @param array   $params Array of parameters (container, user, subtype)
 *
 * @return array
 * @access private
 * @todo this doesn't seem to make a difference if a user can comment or not
 */
function _elgg_comments_container_permissions_override($hook, $type, $return, $params) {

	// is someone trying to comment, if so override permissions check
	if ($params['subtype'] === 'comment') {
		return true;
	}

	return $return;
}
