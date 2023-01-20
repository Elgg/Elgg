<?php
/**
 * Holds helper functions for thewire plugin
 */

/**
 * Get the latest wire guid - used for ajax update
 *
 * @return int
 */
function thewire_latest_guid(): int {
	$post = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'thewire',
		'limit' => 1,
	]);
	
	return $post ? $post[0]->guid : 0;
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
function thewire_save_post(string $text, int $userid, int $access_id, int $parent_guid = 0, string $method = 'site'): int|false {
	
	$post = new \ElggWire();
	$post->owner_guid = $userid;
	$post->access_id = $access_id;

	// Character limit is now from config
	$limit = elgg_get_plugin_setting('limit', 'thewire');
	if ($limit > 0) {
		$text = elgg_substr($text, 0, $limit);
	}
	
	$text = $text ?? '';

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

	return $post->guid;
}

/**
 * Get an array of hashtags from a text string
 *
 * @param string $text The text of a post
 *
 * @return array
 */
function thewire_get_hashtags(string $text): array {
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
function thewire_filter(string $text): string {
	$text = ' ' . $text;

	// email addresses
	$text = elgg_parse_emails($text);

	// links
	$text = elgg_parse_urls($text);

	// usernames
	$text = preg_replace_callback(
		'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
		function ($matches) {
			$username = elgg_extract(2, $matches);
			$url = elgg_generate_url('collection:object:thewire:owner', [
				'username' => $username,
			]);
			$link = elgg_view_url($url, "@{$username}");
			
			return elgg_extract(1, $matches) . $link;
		},
		$text);

	// hashtags
	$text = preg_replace_callback(
		'/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
		function ($matches) {
			$tag = elgg_extract(2, $matches);
			$url = elgg_generate_url('collection:object:thewire:tag', [
				'tag' => $tag,
			]);
			$link = elgg_view_url($url, "#{$tag}");
			
			return elgg_extract(1, $matches) . $link;
		},
		$text);

	return trim($text);
}
