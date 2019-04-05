<?php
/**
 * Elgg wire plugin
 *
 * Forked from Curverider's version
 *
 * JHU/APL Contributors:
 * Cash Costello
 * Clark Updike
 * John Norton
 * Max Thomas
 * Nathan Koterba
 */

use Elgg\Collections\Collection;

/**
 * The Wire initialization
 *
 * @return void
 */
function thewire_init() {

	elgg_register_ajax_view('thewire/previous');

	// add a site navigation item
	elgg_register_menu_item('site', [
		'name' => 'thewire',
		'icon' => 'comments-o',
		'text' => elgg_echo('thewire'),
		'href' => elgg_generate_url('collection:object:thewire:all'),
	]);

	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'thewire_owner_block_menu');

	// remove edit and access and add thread, reply, view previous
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thewire_setup_entity_menu_items');
	
	// Extend system CSS with our own styles, which are defined in the thewire/css view
	elgg_extend_view('elgg.css', 'thewire/css');

	// Register for notifications
	elgg_register_notification_event('object', 'thewire');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:thewire', 'thewire_prepare_notification');
	elgg_register_plugin_hook_handler('get', 'subscriptions', 'thewire_add_original_poster');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:thewire', 'Elgg\Values::getTrue');

	elgg_register_plugin_hook_handler('unit_test', 'system', 'thewire_test');
}

/**
 * Prepare a notification message about a new wire post
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 *
 * @return Elgg\Notifications\Notification
 */
function thewire_prepare_notification($hook, $type, $notification, $params) {

	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];
	$descr = $entity->description;

	$subject = elgg_echo('thewire:notify:subject', [$owner->getDisplayName()], $language);
	if ($entity->reply) {
		$parent = thewire_get_parent($entity->guid);
		if ($parent) {
			$parent_owner = $parent->getOwnerEntity();
			$body = elgg_echo('thewire:notify:reply', [$owner->getDisplayName(), $parent_owner->getDisplayName()], $language);
		}
	} else {
		$body = elgg_echo('thewire:notify:post', [$owner->getDisplayName()], $language);
	}
	$body .= "\n\n" . $descr . "\n\n";
	$body .= elgg_echo('thewire:notify:footer', [$entity->getURL()], $language);

	$notification->subject = $subject;
	$notification->body = $body;
	$notification->summary = elgg_echo('thewire:notify:summary', [$descr], $language);
	$notification->url = $entity->getURL();
	
	return $notification;
}

/**
 * Get an array of hashtags from a text string
 *
 * @param string $text The text of a post
 *
 * @return array
 */
function thewire_get_hashtags($text) {
	// beginning of text or white space followed by hashtag
	// hashtag must begin with # and contain at least one character not digit, space, or punctuation
	$matches = [];
	preg_match_all('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', $text, $matches);
	
	return $matches[2];
}

/**
 * Replace urls, hash tags, and @'s by links
 *
 * @param string $text The text of a post
 *
 * @return string
 */
function thewire_filter($text) {
	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
				'$1<a href="' . elgg_get_site_url() . 'thewire/owner/$2">@$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="' . elgg_get_site_url() . 'thewire/tag/$2">#$2</a>',
				$text);

	return trim($text);
}

/**
 * Create a new wire post.
 *
 * @param string $text        The post text
 * @param int    $userid      The user's guid
 * @param int    $access_id   Public/private etc
 * @param int    $parent_guid Parent post guid (if any)
 * @param string $method      The method (default: 'site')
 *
 * @return false|int
 */
function thewire_save_post($text, $userid, $access_id, $parent_guid = 0, $method = "site") {
	
	$post = new ElggWire();
	$post->owner_guid = $userid;
	$post->access_id = $access_id;

	// Character limit is now from config
	$limit = elgg_get_plugin_setting('limit', 'thewire');
	if ($limit > 0) {
		$text = elgg_substr($text, 0, $limit);
	}

	// no html tags allowed so we escape
	$post->description = htmlspecialchars($text, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');

	$post->method = $method; //method: site, email, api, ...

	$tags = thewire_get_hashtags($text);
	if ($tags) {
		$post->tags = $tags;
	}

	// must do this before saving so notifications pick up that this is a reply
	if ($parent_guid) {
		$post->reply = true;
	}

	$guid = $post->save();
	if ($guid === false) {
		return false;
	}

	// set thread guid
	if ($parent_guid) {
		$post->addRelationship($parent_guid, 'parent');
		
		// name conversation threads by guid of first post (works even if first post deleted)
		$parent_post = get_entity($parent_guid);
		$post->wire_thread = $parent_post->wire_thread;
	} else {
		// first post in this thread
		$post->wire_thread = $guid;
	}

	elgg_create_river_item([
		'view' => 'river/object/thewire/create',
		'action_type' => 'create',
		'subject_guid' => $post->owner_guid,
		'object_guid' => $post->guid,
	]);

	// let other plugins know we are setting a user status
	$params = [
		'entity' => $post,
		'user' => $post->getOwnerEntity(),
		'message' => $post->description,
		'url' => $post->getURL(),
		'origin' => 'thewire',
	];
	elgg_trigger_plugin_hook('status', 'user', $params);
	
	return $guid;
}

/**
 * Add temporary subscription for original poster if not already registered to
 * receive a notification of reply
 *
 * @param string $hook          Hook name
 * @param string $type          Hook type
 * @param array  $subscriptions Subscriptions for a notification event
 * @param array  $params        Parameters including the event
 *
 * @return void|array
 */
function thewire_add_original_poster($hook, $type, $subscriptions, $params) {
	$event = elgg_extract('event', $params);
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}

	if ($event->getAction() !== 'create') {
		return;
	}
	
	$entity = $event->getObject();
	if (!$entity instanceof ElggWire) {
		return;
	}
	
	$parents = $entity->getEntitiesFromRelationship([
		'type' => 'object',
		'subtype' => 'thewire',
		'relationship' => 'parent',
	]);
	if (empty($parents)) {
		return;
	}
	
	/* @var $parent ElggWire */
	$parent = $parents[0];
	// do not add a subscription if reply was to self
	if ($parent->owner_guid === $entity->owner_guid) {
		return;
	}
	
	if (array_key_exists($parent->owner_guid, $subscriptions)) {
		// already in the list
		return;
	}
	
	/* @var $parent_owner ElggUser */
	$parent_owner = $parent->getOwnerEntity();
	$personal_methods = $parent_owner->getNotificationSettings();
	$methods = [];
	foreach ($personal_methods as $method => $state) {
		if ($state) {
			$methods[] = $method;
		}
	}
	
	if (empty($methods)) {
		return;
	}
	
	$subscriptions[$parent->owner_guid] = $methods;
	return $subscriptions;
}

/**
 * Get the latest wire guid - used for ajax update
 *
 * @return int
 */
function thewire_latest_guid() {
	$post = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'thewire',
		'limit' => 1,
	]);
	if ($post) {
		return $post[0]->guid;
	}
	
	return 0;
}

/**
 * Get the parent of a wire post
 *
 * @param int $post_guid The guid of the reply
 *
 * @return void|ElggObject
 */
function thewire_get_parent($post_guid) {
	$parents = elgg_get_entities([
		'relationship' => 'parent',
		'relationship_guid' => $post_guid,
		'limit' => 1,
	]);
	if ($parents) {
		return $parents[0];
	}
}

/**
 * Sets up the entity menu for thewire
 *
 * Adds reply, thread, and view previous links. Removes edit and access.
 *
 * @param string     $hook   'register'
 * @param string     $type   'menu:entity'
 * @param Collection $menu   Array of menu items
 * @param array      $params Array with the entity
 *
 * @return void|Collection
 */
function thewire_setup_entity_menu_items($hook, $type, $menu, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggWire)) {
		return;
	}
	
	$menu->remove('edit');

	if (elgg_is_logged_in()) {
		$menu->add(ElggMenuItem::factory([
			'name' => 'reply',
			'icon' => 'reply',
			'text' => elgg_echo('reply'),
			'href' => elgg_generate_entity_url($entity, 'reply'),
		]));
	}

	if ($entity->reply) {
		$menu->add(ElggMenuItem::factory([
			'name' => 'previous',
			'icon' => 'arrow-left',
			'text' => elgg_echo('previous'),
			'href' => elgg_generate_entity_url($entity, 'previous'),
			'link_class' => 'thewire-previous',
			'title' => elgg_echo('thewire:previous:help'),
		]));
	}

	$menu->add(ElggMenuItem::factory([
		'name' => 'thread',
		'icon' => 'comments-o',
		'text' => elgg_echo('thewire:thread'),
		'href' => elgg_generate_url('collection:object:thewire:thread', [
			'guid' => $entity->wire_thread,
		]),
	]));

	return $menu;
}

/**
 * Add a menu item to an ownerblock
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function thewire_owner_block_menu($hook, $type, $return, $params) {
	
	$user = elgg_extract('entity', $params);
	if (!$user instanceof \ElggUser) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'thewire',
		'text' => elgg_echo('item:object:thewire'),
		'href' => elgg_generate_url('collection:object:thewire:owner', [
			'username' => $user->username,
		]),
	]);
	
	return $return;
}

/**
 * Runs unit tests for the wire
 *
 * @param string $hook   'unit_test'
 * @param string $type   'system'
 * @param array  $value  current return value
 * @param array  $params supplied params
 *
 * @return array
 */
function thewire_test($hook, $type, $value, $params) {
	$value[] = elgg_get_plugins_path() . 'thewire/tests/regex.php';
	return $value;
}

return function() {
	elgg_register_event_handler('init', 'system', 'thewire_init');
};
