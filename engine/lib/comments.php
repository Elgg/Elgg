<?php
/**
 * Elgg comments library
 *
 * @package    Elgg.Core
 * @subpackage Comments
 * @since 1.9
 */

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
	elgg_register_plugin_hook_handler('email', 'system', '_elgg_comments_notification_email_subject');
	
	elgg_register_event_handler('update:after', 'all', '_elgg_comments_access_sync');

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
			if (!($comment instanceof \ElggComment) || !$comment->canEdit()) {
				register_error(elgg_echo('generic_comment:notfound'));
				forward(REFERER);
			}

			$target = $comment->getContainerEntity();
			if (!($target instanceof \ElggEntity)) {
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

		case 'view':
			_elgg_comment_redirect(elgg_extract(1, $page), elgg_extract(2, $page));
			break;

		default:
			return false;
			break;
	}
}

/**
 * Redirect to the comment in context of the containing page
 *
 * @param int $comment_guid  GUID of the comment
 * @param int $fallback_guid GUID of the containing entity
 *
 * @return void
 * @access private
 */
function _elgg_comment_redirect($comment_guid, $fallback_guid) {
	$fail = function () {
		register_error(elgg_echo('generic_comment:notfound'));
		forward(REFERER);
	};

	$comment = get_entity($comment_guid);
	if (!$comment) {
		// try fallback if given
		$fallback = get_entity($fallback_guid);
		if (!$fallback) {
			$fail();
		}

		register_error(elgg_echo('generic_comment:notfound_fallback'));
		forward($fallback->getURL());
	}

	if (!elgg_instanceof($comment, 'object', 'comment')) {
		$fail();
	}

	$container = $comment->getContainerEntity();
	if (!$container) {
		$fail();
	}

	// this won't work with threaded comments, but core doesn't support that yet
	$count = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $container->guid,
		'count' => true,
		'wheres' => ["e.guid < " . (int)$comment->guid],
	]);
	$limit = (int)get_input('limit');
	if (!$limit) {
		$limit = elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
	}
	$offset = floor($count / $limit) * $limit;
	if (!$offset) {
		$offset = null;
	}

	$url = elgg_http_add_url_query_elements($container->getURL(), [
			'offset' => $offset,
		]) . "#elgg-object-{$comment->guid}";

	forward($url);
}

/**
 * Setup the menu shown with a comment
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:entity'
 * @param \ElggMenuItem[] $return Array of \ElggMenuItem objects
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
	/* @var \ElggObject $entity */

	if (!elgg_instanceof($entity, 'object', 'comment') || !$entity->getOwnerEntity()) {
		// not a comment or has no owner

		// @todo handle anonymous comments
		return $return;
	}

	$container = $entity->getContainerEntity();
	if (!$container) {
		return $return;
	}

	return "comment/view/{$entity->guid}/{$container->guid}";
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

/**
 * Set subject for email notifications about new ElggComment objects
 *
 * The "Re: " part is required by some email clients in order to properly
 * group the notifications in threads.
 *
 * Group discussion replies extend ElggComment objects so this takes care
 * of their notifications also.
 *
 * @param string $hook        'email'
 * @param string $type        'system'
 * @param array  $returnvalue Current mail parameters
 * @param array  $params      Original mail parameters
 * @return array $returnvalue Modified mail parameters
 */
function _elgg_comments_notification_email_subject($hook, $type, $returnvalue, $params) {
	if (!is_array($returnvalue)) {
		// another hook handler returned a non-array, let's not override it
		return;
	}

	/** @var Elgg\Notifications\Notification */
	$notification = elgg_extract('notification', $returnvalue['params']);

	if ($notification instanceof Elgg\Notifications\Notification) {
		$object = elgg_extract('object', $notification->params);

		if ($object instanceof ElggComment) {
			$container = $object->getContainerEntity();

			$returnvalue['subject'] = 'Re: ' . $container->getDisplayName();
		}
	}

	return $returnvalue;
}

/**
 * Update comment access to match that of the container
 * 
 * @param string     $event  'update:after'
 * @param string     $type   'all'
 * @param ElggEntity $entity The updated entity
 * @return bool
 * 
 * @access private
 */
function _elgg_comments_access_sync($event, $type, $entity) {
	if (!($entity instanceof \ElggEntity)) {
		return true;
	}
	
	// need to override access in case comments ended up with ACCESS_PRIVATE
	// and to ensure write permissions
	$ia = elgg_set_ignore_access(true);
	$options = array(
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $entity->getGUID(),
		'wheres' => array(
			"e.access_id != {$entity->access_id}"
		),
		'limit' => 0,
	);

	$batch = new \ElggBatch('elgg_get_entities', $options, null, 25, false);
	foreach ($batch as $comment) {
		// Update comment access_id
		$comment->access_id = $entity->access_id;
		$comment->save();
	}
		
	elgg_set_ignore_access($ia);
	
	return true;
}

/**
 * Runs unit tests for \ElggComment
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_comments_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggCommentTest.php";
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_comments_init');
	$hooks->registerHandler('unit_test', 'system', '_elgg_comments_test');
};
