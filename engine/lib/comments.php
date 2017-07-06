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
	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_comment_url_handler');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '_elgg_comments_container_permissions_override');
	elgg_register_plugin_hook_handler('permissions_check', 'object', '_elgg_comments_permissions_override');
	elgg_register_plugin_hook_handler('email', 'system', '_elgg_comments_notification_email_subject');
	
	elgg_register_event_handler('update:after', 'all', '_elgg_comments_access_sync', 600);

	elgg_register_page_handler('comment', '_elgg_comments_page_handler');

	elgg_register_ajax_view('core/ajax/edit_comment');

	elgg_register_plugin_hook_handler('likes:is_likable', 'object:comment', 'Elgg\Values::getTrue');
	
	elgg_register_notification_event('object', 'comment', ['create']);
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_comments_add_content_owner_to_subscriptions');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', '_elgg_comments_prepare_content_owner_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', '_elgg_comments_prepare_notification');
}

/**
 * Page handler for generic comments manipulation.
 *
 * @param array $segments
 * @return bool
 * @access private
 */
function _elgg_comments_page_handler($segments) {

	$page = elgg_extract(0, $segments);
	switch ($page) {
		case 'edit':
			echo elgg_view_resource('comments/edit', [
				'guid' => elgg_extract(1, $segments),
			]);
			return true;
			break;

		case 'view':
			_elgg_comment_redirect(elgg_extract(1, $segments), elgg_extract(2, $segments));
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
		'wheres' => ["e.guid < " . (int) $comment->guid],
	]);
	$limit = (int) get_input('limit');
	if (!$limit) {
		$limit = elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
	}
	$offset = floor($count / $limit) * $limit;
	if (!$offset) {
		$offset = null;
	}

	$url = elgg_http_add_url_query_elements($container->getURL(), [
		'offset' => $offset,
	]);
	
	// make sure there's only one fragment (#)
	$parts = parse_url($url);
	$parts['fragment'] = "elgg-object-{$comment->guid}";
	$url = elgg_http_build_url($parts, false);
	
	forward($url);
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
 * @access private
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
 * @access private
 */
function _elgg_comments_notification_email_subject($hook, $type, $returnvalue, $params) {
	if (!is_array($returnvalue) || !is_array($returnvalue['params'])) {
		// another hook handler returned a non-array, let's not override it
		return;
	}

	if (empty($returnvalue['params']['notification'])) {
		return;
	}
	
	/** @var Elgg\Notifications\Notification */
	$notification = $returnvalue['params']['notification'];

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
	$options = [
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $entity->getGUID(),
		'wheres' => [
			"e.access_id != {$entity->access_id}"
		],
		'limit' => 0,
	];

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
 * Add the owner of the content being commented on to the subscribers
 *
 * @param string $hook        'get'
 * @param string $type        'subscribers'
 * @param array  $returnvalue current subscribers
 * @param array  $params      supplied params
 *
 * @return void|array
 *
 * @access private
 */
function _elgg_comments_add_content_owner_to_subscriptions($hook, $type, $returnvalue, $params) {
	
	$event = elgg_extract('event', $params);
	if (!($event instanceof \Elgg\Notifications\Event)) {
		return;
	}
	
	$object = $event->getObject();
	if (!elgg_instanceof($object, 'object', 'comment')) {
		// can't use instanceof ElggComment as discussion replies inherit
		return;
	}
	
	$content_owner = $object->getContainerEntity()->getOwnerEntity();
	if (!($content_owner instanceof ElggUser)) {
		return;
	}
	
	$notification_settings = get_user_notification_settings($content_owner->getGUID());
	if (empty($notification_settings)) {
		return;
	}
	
	$returnvalue[$content_owner->getGUID()] = [];
	foreach ($notification_settings as $method => $enabled) {
		if (empty($enabled)) {
			continue;
		}
		
		$returnvalue[$content_owner->getGUID()][] = $method;
	}
	
	return $returnvalue;
}

/**
 * Set the notification message for the owner of the content being commented on
 *
 * @param string                           $hook        'prepare'
 * @param string                           $type        'notification:create:object:comment'
 * @param \Elgg\Notifications\Notification $returnvalue current notification message
 * @param array                            $params      supplied params
 *
 * @return void|\Elgg\Notifications\Notification
 *
 * @access private
 */
function _elgg_comments_prepare_content_owner_notification($hook, $type, $returnvalue, $params) {
	
	$comment = elgg_extract('object', $params);
	if (!elgg_instanceof($comment, 'object', 'comment')) {
		// can't use instanceof ElggComment as discussion replies inherit
		return;
	}
	
	/* @var $content \ElggEntity */
	$content = $comment->getContainerEntity();
	$recipient = elgg_extract('recipient', $params);
	if ($content->owner_guid !== $recipient->guid) {
		// not the content owner
		return;
	}
	
	$language = elgg_extract('language', $params);
	/* @var $commenter \ElggUser */
	$commenter = $comment->getOwnerEntity();
	
	$returnvalue->subject = elgg_echo('generic_comment:notification:owner:subject', [], $language);
	$returnvalue->summary = elgg_echo('generic_comment:notification:owner:summary', [], $language);
	$returnvalue->body = elgg_echo('generic_comment:notification:owner:body', [
		$content->getDisplayName(),
		$commenter->getDisplayName(),
		$comment->description,
		$comment->getURL(),
		$commenter->getDisplayName(),
		$commenter->getURL(),
	], $language);
	
	return $returnvalue;
}

/**
 * Set the notification message for interested users
 *
 * @param string                           $hook        'prepare'
 * @param string                           $type        'notification:create:object:comment'
 * @param \Elgg\Notifications\Notification $returnvalue current notification message
 * @param array                            $params      supplied params
 *
 * @return void|\Elgg\Notifications\Notification
 *
 * @access private
 */
function _elgg_comments_prepare_notification($hook, $type, $returnvalue, $params) {
	
	$comment = elgg_extract('object', $params);
	if (!elgg_instanceof($object, 'object', 'comment')) {
		// can't use instanceof ElggComment as discussion replies inherit
		return;
	}
	
	/* @var $content \ElggEntity */
	$content = $object->getContainerEntity();
	$recipient = elgg_extract('recipient', $params);
	if ($content->getOwnerGUID() === $recipient->getGUID()) {
		// the content owner, this is handled in other hook
		return;
	}
	
	$language = elgg_extract('language', $params);
	/* @var $commenter \ElggUser */
	$commenter = $comment->getOwnerEntity();
	
	$returnvalue->subject = elgg_echo('generic_comment:notification:user:subject', [$content->getDisplayName()], $language);
	$returnvalue->summary = elgg_echo('generic_comment:notification:user:summary', [$content->getDisplayName()], $language);
	$returnvalue->body = elgg_echo('generic_comment:notification:user:body', [
		$content->getDisplayName(),
		$commenter->getDisplayName(),
		$comment->description,
		$comment->getURL(),
		$commenter->getDisplayName(),
		$commenter->getURL(),
	], $language);

	$returnvalue->url = $comment->getURL();
	
	return $returnvalue;
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
