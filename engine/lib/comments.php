<?php
/**
 * Elgg comments library
 *
 * @package    Elgg.Core
 * @subpackage Comments
 * @since 1.9
 */

use Elgg\Database\QueryBuilder;

/**
 * Comments initialization function
 *
 * @return void
 * @access private
 */
function _elgg_comments_init() {
	elgg_register_entity_type('object', 'comment');

	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '_elgg_comments_container_permissions_override');
	elgg_register_plugin_hook_handler('permissions_check', 'object', '_elgg_comments_permissions_override');
	elgg_register_plugin_hook_handler('email', 'system', '_elgg_comments_notification_email_subject');
	
	elgg_register_plugin_hook_handler('register', 'menu:social', '_elgg_comments_social_menu_setup');
	
	elgg_register_event_handler('update:after', 'all', '_elgg_comments_access_sync', 600);

	elgg_register_ajax_view('core/ajax/edit_comment');
	elgg_register_ajax_view('page/elements/comments');
	elgg_register_ajax_view('river/elements/responses');

	elgg_register_plugin_hook_handler('likes:is_likable', 'object:comment', 'Elgg\Values::getTrue');
	
	elgg_register_notification_event('object', 'comment', ['create']);
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_comments_add_content_owner_to_subscriptions');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', '_elgg_comments_prepare_content_owner_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', '_elgg_comments_prepare_notification');
}

/**
 * Are comments displayed with latest first?
 *
 * @param ElggEntity $container Entity containing comments
 * @return bool False means oldest first.
 * @since 3.0
 */
function elgg_comments_are_latest_first(ElggEntity $container = null) {
	$params = [
		'entity' => $container,
	];
	return (bool) elgg_trigger_plugin_hook('config', 'comments_latest_first', $params, true);
}

/**
 * How many comments appear per page.
 *
 * @param ElggEntity $container Entity containing comments
 * @return int
 * @since 3.0
 */
function elgg_comments_per_page(ElggEntity $container = null) {
	$params = [
		'entity' => $container,
	];
	return (int) elgg_trigger_plugin_hook('config', 'comments_per_page', $params, 25);
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

	if (!$comment instanceof ElggComment) {
		$fail();
	}

	$container = $comment->getContainerEntity();
	if (!$container) {
		$fail();
	}

	$operator = elgg_comments_are_latest_first($container) ? '>' : '<';

	// this won't work with threaded comments, but core doesn't support that yet
	$condition = function(QueryBuilder $qb, $main_alias) use ($comment, $operator) {
		return $qb->compare("{$main_alias}.guid", $operator, $comment->guid, ELGG_VALUE_GUID);
	};
	$count = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $container->guid,
		'count' => true,
		'wheres' => [$condition],
	]);
	$limit = (int) get_input('limit');
	if (!$limit) {
		$limit = elgg_comments_per_page($container);
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
	
	if ($entity instanceof ElggComment && $user) {
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
		'wheres' => [function(\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
			return $qb->compare("{$main_alias}.access_id", '!=', $entity->access_id, ELGG_VALUE_INTEGER);
		}],
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
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if ($event->getAction() !== 'create') {
		return;
	}
	
	$object = $event->getObject();
	if (!$object instanceof ElggComment) {
		return;
	}
	
	$content_owner = $object->getContainerEntity()->getOwnerEntity();
	if (!$content_owner instanceof ElggUser) {
		return;
	}
	
	$notification_settings = $content_owner->getNotificationSettings();
	if (empty($notification_settings)) {
		return;
	}
	
	$returnvalue[$content_owner->guid] = [];
	foreach ($notification_settings as $method => $enabled) {
		if (empty($enabled)) {
			continue;
		}
		
		$returnvalue[$content_owner->guid][] = $method;
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
	if (!$comment instanceof ElggComment) {
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
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	/* @var $content \ElggEntity */
	$content = $comment->getContainerEntity();
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
 * Adds comment menu items to entity menu
 *
 * @param \Elgg\Hook $hook Hook information
 *
 * @return void|\ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _elgg_comments_social_menu_setup(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity) {
		return;
	}
	
	$return = $hook->getValue();
	
	$comment_count = $entity->countComments();
	$can_comment = $entity->canComment();
	if ($can_comment || $comment_count) {
		$text = $can_comment ? elgg_echo('comment:this') : elgg_echo('comments');
		
		$options = [
			'name' => 'comment',
			'icon' => 'speech-bubble',
			'badge' => $comment_count ?: null,
			'text' => $text,
			'title' => $text,
			'href' => $entity->getURL() . '#comments',
		];
				
		$item = $hook->getParam('item');
		if ($item && $can_comment) {
			$options['href'] = "#comments-add-{$entity->guid}-{$item->id}";
			$options['rel'] = 'toggle';
		}
		
		$return[] = \ElggMenuItem::factory($options);
	}
	
	return $return;
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
 * @codeCoverageIgnore
 */
function _elgg_comments_test($hook, $type, $value, $params) {
	$value[] = ElggCoreCommentTest::class;
	return $value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_comments_init');
	$hooks->registerHandler('unit_test', 'system', '_elgg_comments_test');
};
