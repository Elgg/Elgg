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

elgg_register_event_handler('init', 'system', 'thewire_init');

/**
 * The Wire initialization
 */
function thewire_init() {

	// register the wire's JavaScript
	$thewire_js = elgg_get_simplecache_url('js', 'thewire');
	elgg_register_js('elgg.thewire', $thewire_js, 'footer');

	elgg_register_ajax_view('thewire/previous');

	// add a site navigation item
	$item = new ElggMenuItem('thewire', elgg_echo('thewire'), 'thewire/all');
	elgg_register_menu_item('site', $item);

	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'thewire_owner_block_menu');

	// remove edit and access and add thread, reply, view previous
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thewire_setup_entity_menu_items');
	
	// Extend system CSS with our own styles, which are defined in the thewire/css view
	elgg_extend_view('css/elgg', 'thewire/css');

	// Add a user's latest wire post to profile
	elgg_extend_view('profile/status', 'thewire/profile_status');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('thewire', 'thewire_page_handler');

	// Register a URL handler for thewire posts
	elgg_register_plugin_hook_handler('entity:url', 'object', 'thewire_set_url');

	elgg_register_widget_type('thewire', elgg_echo('thewire'), elgg_echo("thewire:widget:desc"));

	// Register for search
	elgg_register_entity_type('object', 'thewire');

	// Register for notifications
	elgg_register_notification_event('object', 'thewire');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:thewire', 'thewire_prepare_notification');
	elgg_register_plugin_hook_handler('get', 'subscriptions', 'thewire_add_original_poster');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'thewire/actions';
	elgg_register_action("thewire/add", "$action_base/add.php");
	elgg_register_action("thewire/delete", "$action_base/delete.php");

	elgg_register_plugin_hook_handler('unit_test', 'system', 'thewire_test');

	elgg_register_event_handler('upgrade', 'system', 'thewire_run_upgrades');
}

/**
 * The wire page handler
 *
 * Supports:
 * thewire/all                  View site wire posts
 * thewire/owner/<username>     View this user's wire posts
 * thewire/following/<username> View the posts of those this user follows
 * thewire/reply/<guid>         Reply to a post
 * thewire/view/<guid>          View a post
 * thewire/thread/<id>          View a conversation thread
 * thewire/tag/<tag>            View wire posts tagged with <tag>
 *
 * @param array $page From the page_handler function
 * @return bool
 */
function thewire_page_handler($page) {

	$base_dir = elgg_get_plugins_path() . 'thewire/pages/thewire';

	if (!isset($page[0])) {
		$page = array('all');
	}

	switch ($page[0]) {
		case "all":
			include "$base_dir/everyone.php";
			break;

		case "friends":
			include "$base_dir/friends.php";
			break;

		case "owner":
			include "$base_dir/owner.php";
			break;

		case "view":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/view.php";
			break;

		case "thread":
			if (isset($page[1])) {
				set_input('thread_id', $page[1]);
			}
			include "$base_dir/thread.php";
			break;

		case "reply":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/reply.php";
			break;

		case "tag":
			if (isset($page[1])) {
				set_input('tag', $page[1]);
			}
			include "$base_dir/tag.php";
			break;

		case "previous":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/previous.php";
			break;

		default:
			return false;
	}
	return true;
}

/**
 * Override the url for a wire post to return the thread
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function thewire_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'thewire')) {
		return "thewire/view/" . $entity->guid;
	}
}

/**
 * Prepare a notification message about a new wire post
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function thewire_prepare_notification($hook, $type, $notification, $params) {

	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];
	$descr = $entity->description;

	$subject = elgg_echo('thewire:notify:subject', array($owner->name), $language);
	if ($entity->reply) {
		$parent = thewire_get_parent($entity->guid);
		if ($parent) {
			$parent_owner = $parent->getOwnerEntity();
			$body = elgg_echo('thewire:notify:reply', array($owner->name, $parent_owner->name), $language);
		}
	} else {
		$body = elgg_echo('thewire:notify:post', array($owner->name), $language);
	}
	$body .= "\n\n" . $descr . "\n\n";
	$body .= elgg_echo('thewire:notify:footer', array($entity->getURL()), $language);

	$notification->subject = $subject;
	$notification->body = $body;
	$notification->summary = elgg_echo('thewire:notify:summary', array($descr), $language);

	return $notification;
}

/**
 * Get an array of hashtags from a text string
 *
 * @param string $text The text of a post
 * @return array
 */
function thewire_get_hashtags($text) {
	// beginning of text or white space followed by hashtag
	// hashtag must begin with # and contain at least one character not digit, space, or punctuation
	$matches = array();
	preg_match_all('/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/', $text, $matches);
	return $matches[2];
}

/**
 * Replace urls, hash tags, and @'s by links
 *
 * @param string $text The text of a post
 * @return string
 */
function thewire_filter($text) {
	global $CONFIG;

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
				'$1<a href="' . $CONFIG->wwwroot . 'thewire/owner/$2">@$2</a>',
				$text);

	// hashtags
	$text = preg_replace(
				'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
				'$1<a href="' . $CONFIG->wwwroot . 'thewire/tag/$2">#$2</a>',
				$text);

	$text = trim($text);

	return $text;
}

/**
 * Create a new wire post.
 *
 * @param string $text        The post text
 * @param int    $userid      The user's guid
 * @param int    $access_id   Public/private etc
 * @param int    $parent_guid Parent post guid (if any)
 * @param string $method      The method (default: 'site')
 * @return guid or false if failure
 */
function thewire_save_post($text, $userid, $access_id, $parent_guid = 0, $method = "site") {
	$post = new ElggObject();

	$post->subtype = "thewire";
	$post->owner_guid = $userid;
	$post->access_id = $access_id;

	// Character limit is now from config
	$limit = elgg_get_plugin_setting('limit', 'thewire');
	if ($limit > 0) {
		$text = elgg_substr($text, 0, $limit);
	}

	// no html tags allowed so we escape
	$post->description = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

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

	if ($guid) {
		elgg_create_river_item(array(
			'view' => 'river/object/thewire/create',
			'action_type' => 'create',
			'subject_guid' => $post->owner_guid,
			'object_guid' => $post->guid,
		));

		// let other plugins know we are setting a user status
		$params = array(
			'entity' => $post,
			'user' => $post->getOwnerEntity(),
			'message' => $post->description,
			'url' => $post->getURL(),
			'origin' => 'thewire',
		);
		elgg_trigger_plugin_hook('status', 'user', $params);
	}
	
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
 * @return array
 */
function thewire_add_original_poster($hook, $type, $subscriptions, $params) {
	$event = $params['event'];
	$entity = $event->getObject();
	if ($entity && elgg_instanceof($entity, 'object', 'thewire')) {
		$parent = $entity->getEntitiesFromRelationship(array('relationship' => 'parent'));
		if ($parent) {
			$parent = $parent[0];
			// do not add a subscription if reply was to self
			if ($parent->getOwnerGUID() !== $entity->getOwnerGUID()) {
				if (!array_key_exists($parent->getOwnerGUID(), $subscriptions)) {
					$personal_methods = (array)get_user_notification_settings($parent->getOwnerGUID());
					$methods = array();
					foreach ($personal_methods as $method => $state) {
						if ($state) {
							$methods[] = $method;
						}
					}
					if ($methods) {
						$subscriptions[$parent->getOwnerGUID()] = $methods;
						return $subscriptions;
					}
				}
			}
		}
	}
}

/**
 * Get the latest wire guid - used for ajax update
 *
 * @return guid
 */
function thewire_latest_guid() {
	$post = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'thewire',
		'limit' => 1,
	));
	if ($post) {
		return $post[0]->guid;
	} else {
		return 0;
	}
}

/**
 * Get the parent of a wire post
 *
 * @param int $post_guid The guid of the reply
 * @return ElggObject or null
 */
function thewire_get_parent($post_guid) {
	$parents = elgg_get_entities_from_relationship(array(
		'relationship' => 'parent',
		'relationship_guid' => $post_guid,
	));
	if ($parents) {
		return $parents[0];
	}
	return null;
}

/**
 * Sets up the entity menu for thewire
 *
 * Adds reply, thread, and view previous links. Removes edit and access.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Array of menu items
 * @param array  $params Array with the entity
 * @return array
 */
function thewire_setup_entity_menu_items($hook, $type, $value, $params) {
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'thewire') {
		return $value;
	}

	foreach ($value as $index => $item) {
		$name = $item->getName();
		if ($name == 'access' || $name == 'edit') {
			unset($value[$index]);
		}
	}

	$entity = $params['entity'];

	if (elgg_is_logged_in()) {
		$options = array(
			'name' => 'reply',
			'text' => elgg_echo('reply'),
			'href' => "thewire/reply/$entity->guid",
			'priority' => 150,
		);
		$value[] = ElggMenuItem::factory($options);
	}

	if ($entity->reply) {
		$options = array(
			'name' => 'previous',
			'text' => elgg_echo('previous'),
			'href' => "thewire/previous/$entity->guid",
			'priority' => 160,
			'link_class' => 'thewire-previous',
			'title' => elgg_echo('thewire:previous:help'),
		);
		$value[] = ElggMenuItem::factory($options);
	}

	$options = array(
		'name' => 'thread',
		'text' => elgg_echo('thewire:thread'),
		'href' => "thewire/thread/$entity->wire_thread",
		'priority' => 170,
	);
	$value[] = ElggMenuItem::factory($options);

	return $value;
}

/**
 * Add a menu item to an ownerblock
 *
 * @return array
 */
function thewire_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "thewire/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('thewire', elgg_echo('item:object:thewire'), $url);
		$return[] = $item;
	}

	return $return;
}

/**
 * Runs unit tests for the wire
 *
 * @return array
 */
function thewire_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->pluginspath . 'thewire/tests/regex.php';
	return $value;
}

function thewire_run_upgrades() {
	$path = dirname(__FILE__) . '/upgrades/';
	$files = elgg_get_upgrade_files($path);
	
	foreach ($files as $file) {
		include $path . $file;
	}
}
