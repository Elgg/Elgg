<?php
/**
 * Elgg comments library
 *
 * @since 1.9
 */

use Elgg\Database\QueryBuilder;

/**
 * Comments initialization function
 *
 * @return void
 * @internal
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
	return (bool) elgg_trigger_plugin_hook('config', 'comments_latest_first', $params, (bool) _elgg_config()->comments_latest_first);
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
 * @internal
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
	$count = elgg_count_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $container->guid,
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
 * @param \Elgg\Hook $hook 'container_permissions_check', 'object'
 *
 * @return void|true
 * @internal
 * @todo this doesn't seem to make a difference if a user can comment or not
 */
function _elgg_comments_container_permissions_override(\Elgg\Hook $hook) {
	// is someone trying to comment, if so override permissions check
	if ($hook->getParam('subtype') === 'comment') {
		return true;
	}
}

/**
 * By default, only authors can edit their comments.
 *
 * @param \Elgg\Hook $hook 'permissions_check', 'object'
 *
 * @return void|boolean Whether the given user is allowed to edit the given comment.
 * @internal
 */
function _elgg_comments_permissions_override(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	$user = $hook->getUserParam();
	
	if (!$entity instanceof ElggComment || !$user instanceof ElggUser) {
		return;
	}
	
	$return = function () use ($entity, $user) {
		return $entity->owner_guid === $user->guid;
	};
	
	$content = $entity->getContainerEntity();
	if (!$content instanceof ElggEntity) {
		return $return();
	}
	
	$container = $content->getContainerEntity();
	
	// use default access for group editors to moderate comments
	if ($container instanceof ElggGroup && $container->canEdit($user->guid)) {
		return;
	}
	
	return $return();
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
 * @param \Elgg\Hook $hook 'email', 'system'
 *
 * @return array $returnvalue Modified mail parameters
 * @internal
 */
function _elgg_comments_notification_email_subject(\Elgg\Hook $hook) {
	$returnvalue = $hook->getValue();
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
 * @param \Elgg\Event $event 'update:after', 'all'
 *
 * @return bool
 *
 * @internal
 */
function _elgg_comments_access_sync(\Elgg\Event $event) {
	$entity = $event->getObject();
	if (!$entity instanceof \ElggEntity) {
		return true;
	}
	
	// need to override access in case comments ended up with ACCESS_PRIVATE
	// and to ensure write permissions
	elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity) {
		$comments = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $entity->guid,
			'wheres' => [function(\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
				return $qb->compare("{$main_alias}.access_id", '!=', $entity->access_id, ELGG_VALUE_INTEGER);
			}],
			'limit' => 0,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
	
		foreach ($comments as $comment) {
			// Update comment access_id
			$comment->access_id = $entity->access_id;
			$comment->save();
		}
	});
	
	return true;
}

/**
 * Add the owner of the content being commented on to the subscribers
 *
 * @param \Elgg\Hook $hook 'get', 'subscribers'
 *
 * @return void|array
 *
 * @internal
 */
function _elgg_comments_add_content_owner_to_subscriptions(\Elgg\Hook $hook) {
	
	$event = $hook->getParam('event');
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
	
	$returnvalue = $hook->getValue();
	
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
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:comment'
 *
 * @return void|\Elgg\Notifications\Notification
 *
 * @internal
 */
function _elgg_comments_prepare_content_owner_notification(\Elgg\Hook $hook) {
	
	$comment = $hook->getParam('object');
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	/* @var $content \ElggEntity */
	$content = $comment->getContainerEntity();
	$recipient = $hook->getParam('recipient');
	if ($content->owner_guid !== $recipient->guid) {
		// not the content owner
		return;
	}
	
	$language = $hook->getParam('language');
	/* @var $commenter \ElggUser */
	$commenter = $comment->getOwnerEntity();
	
	$returnvalue = $hook->getValue();
	
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
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:comment'
 *
 * @return void|\Elgg\Notifications\Notification
 *
 * @internal
 */
function _elgg_comments_prepare_notification(\Elgg\Hook $hook) {
	
	$comment = $hook->getParam('object');
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	/* @var $content \ElggEntity */
	$content = $comment->getContainerEntity();
	$recipient = $hook->getParam('recipient');
	if ($content->getOwnerGUID() === $recipient->getGUID()) {
		// the content owner, this is handled in other hook
		return;
	}
	
	$language = $hook->getParam('language');
	/* @var $commenter \ElggUser */
	$commenter = $comment->getOwnerEntity();
	
	$returnvalue = $hook->getValue();
	
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
 * @internal
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
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events) {
	$events->registerHandler('init', 'system', '_elgg_comments_init');
};
