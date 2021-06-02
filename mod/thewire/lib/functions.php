<?php
/**
 * Holds helper functions for thewire plugin
 */

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
	if (!empty($tags)) {
		$post->tags = $tags;
	}

	// must do this before saving so notifications pick up that this is a reply
	if ($parent_guid) {
		$post->reply = true;
	}

	if (!$post->save()) {
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
		$post->wire_thread = $post->guid;
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
	
	return $post->guid;
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
