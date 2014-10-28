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
	elgg_register_plugin_hook_handler('permissions_check', 'object', '_elgg_comments_permissions_override');

	elgg_register_page_handler('comment', '_elgg_comments_page_handler');

	elgg_register_ajax_view('core/ajax/edit_comment');
}

/**
 * Page handler for generic comments manipulation.
 *
 * @param array $page
 * @return bool
 * @access private
 */
function _elgg_comments_page_handler($page) {

	switch ($page[0]) {

		case 'edit':
			elgg_gatekeeper();

			if (empty($page[1])) {
				register_error(elgg_echo('generic_comment:notfound'));
				forward(REFERER);
			}
			$comment = get_entity($page[1]);
			if (!($comment instanceof ElggComment) || !$comment->canEdit()) {
				register_error(elgg_echo('generic_comment:notfound'));
				forward(REFERER);
			}

			$target = $comment->getContainerEntity();
			if (!($target instanceof ElggEntity)) {
				register_error(elgg_echo('generic_comment:notfound'));
				forward(REFERER);
			}

			$title = elgg_echo('generic_comments:edit');
			elgg_push_breadcrumb($target->getDisplayName(), $target->getURL());
			elgg_push_breadcrumb($title);

			$params = array(
				'entity' => $target,
				'comment' => $comment,
				'is_edit_page' => true,
			);
			$content = elgg_view_form('comment/save', null, $params);

			$params = array(
				'content' => $content,
				'title' => $title,
				'filter' => '',
			);
			$body = elgg_view_layout('content', $params);
			echo elgg_view_page($title, $body);

			return true;
			break;

		default:
			return false;
			break;
	}
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

/**
 * By default, only authors can edit their comments.
 *
 * @param string  $hook   'permissions_check'
 * @param string  $type   'object'
 * @param boolean $return Can the given user edit the given entity?
 * @param array   $params Array of parameters (entity, user)
 *
 * @return boolean Whether the given user is allowed to edit the given comment.
 */
function _elgg_comments_permissions_override($hook, $type, $return, $params) {
	$entity = $params['entity'];
	$user = $params['user'];

	if (elgg_instanceof($entity, 'object', 'comment') && $user) {
		return $entity->getOwnerGUID() == $user->getGUID();
	}

	return $return;
}
