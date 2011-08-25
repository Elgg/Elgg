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
	global $CONFIG;

	if (!update_subtype('object', 'thewire', 'ElggWire')) {
		add_subtype('object', 'thewire', 'ElggWire');
	}

	// register the wire's JavaScript
	$thewire_js = elgg_get_simplecache_url('js', 'thewire');
	elgg_register_js('elgg.thewire', $thewire_js, 'footer');

	// add a site navigation item
	$item = new ElggMenuItem('thewire', elgg_echo('thewire'), 'thewire/all');
	elgg_register_menu_item('site', $item);

	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'thewire_owner_block_menu');

	// remove edit and access and add thread, reply, view previous
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thewire_setup_entity_menu_items');
	
	// Extend system CSS with our own styles, which are defined in the thewire/css view
	elgg_extend_view('css', 'thewire/css');

	//extend views
	elgg_extend_view('activity/thewire', 'thewire/activity_view');
	elgg_extend_view('profile/status', 'thewire/profile_status');
	elgg_extend_view('js/initialise_elgg', 'thewire/js/textcounter');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('thewire', 'thewire_page_handler');

	// Register a URL handler for thewire posts
	elgg_register_entity_url_handler('object', 'thewire', 'thewire_url');

	elgg_register_widget_type('thewire', elgg_echo('thewire'), elgg_echo("thewire:widget:desc"));

	// Register for search
	elgg_register_entity_type('object', 'thewire');

	// Register granular notification for this type
	register_notification_object('object', 'thewire', elgg_echo('thewire:notify:subject'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'thewire_notify_message');

	// Register actions
	$action_base = $CONFIG->pluginspath . 'thewire/actions';
	elgg_register_action("thewire/add", "$action_base/add.php");
	elgg_register_action("thewire/delete", "$action_base/delete.php");

	elgg_register_plugin_hook_handler('unit_test', 'system', 'thewire_test');
}

/**
 * The wire page handler
 *
 * Supports:
 * thewire/all                  View site wire posts
 * thewire/owner/<username>     View this user's wire posts
 * thewire/following/<username> View the posts of those this user follows
 * thewire/reply/<guid>         Reply to a post
 * thewire/view/<guid>          View a conversation thread
 * thewire/tag/<tag>            View wire posts tagged with <tag>
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function thewire_page_handler($page) {

	$base_dir = elgg_get_plugins_path() . 'thewire/pages/thewire';

	// if just /thewire go to global view in the else statement
	if (isset($page[0]) && $page[0]) {

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
		}
	} else {
		include "$base_dir/everyone.php";
	}

	return true;
}

/**
 * Override the url for a wire post to return the thread
 * 
 * @param ElggObject $thewirepost Wire post object
 */
function thewire_url($thewirepost) {
	global $CONFIG;
	return $CONFIG->url . "thewire/view/" . $thewirepost->guid;
}

/**
 * Returns the notification body
 *
 * @return $string
 */
function thewire_notify_message($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;
	
	$entity = $params['entity'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'thewire')) {
		$descr = $entity->description;
		$owner = $entity->getOwnerEntity();
		if ($entity->reply) {
			// have to do this because of poor design of Elgg notification system
			$parent_post = get_entity(get_input('parent_guid'));
			if ($parent_post) {
				$parent_owner = $parent_post->getOwnerEntity();
			}
			$body = sprintf(elgg_echo('thewire:notify:reply'), $owner->name, $parent_owner->name);
		} else {
			$body = sprintf(elgg_echo('thewire:notify:post'), $owner->name);
		}
		$body .= "\n\n" . $descr . "\n\n";
		$body .= elgg_echo('thewire') . ": {$CONFIG->url}thewire";
		return $body;
	}
	return $returnvalue;
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
				'/(^|[^\w])@([\w]+)/',
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

	// only 200 characters allowed
	$text = elgg_substr($text, 0, 200);

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
		add_to_river('river/object/thewire/create', 'create', $post->owner_guid, $post->guid);

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
 * Send notification to poster of parent post if not notified already
 *
 * @param int      $guid        The guid of the reply wire post
 * @param int      $parent_guid The guid of the original wire post
 * @param ElggUser $user        The user who posted the reply
 * @return void
 */
function thewire_send_response_notification($guid, $parent_guid, $user) {
	$parent_owner = get_entity($parent_guid)->getOwnerEntity();
	$user = get_loggedin_user();

	// check to make sure user is not responding to self
	if ($parent_owner->guid != $user->guid) {
		// check if parent owner has notification for this user
		$send_response = true;
		global $NOTIFICATION_HANDLERS;
		foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
			if (check_entity_relationship($parent_owner->guid, 'notify' . $method, $user->guid)) {
				$send_response = false;
			}
		}

		// create the notification message
		if ($send_response) {
			// grab same notification message that goes to everyone else
			$params = array(
				'entity' => get_entity($guid),
				'method' => "email",
			);
			$msg = thewire_notify_message("", "", "", $params);

			notify_user(
					$parent_owner->guid,
					$user->guid,
					elgg_echo('thewire:notify:subject'),
					$msg);
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
			'text' => elgg_echo('thewire:reply'),
			'href' => "thewire/reply/$entity->guid",
			'priority' => 150,
		);
		$value[] = ElggMenuItem::factory($options);
	}

	if ($entity->reply) {
		$options = array(
			'name' => 'previous',
			'text' => elgg_echo('thewire:previous'),
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
