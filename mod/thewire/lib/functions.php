<?php
/**
 * Holds helper functions for thewire plugin
 */

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
 *
 * @deprecated 6.2
 */
function thewire_save_post(string $text, int $userid, int $access_id, int $parent_guid = 0, string $method = 'site'): int|false {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.2');
	
	$post = new \ElggWire();
	$post->owner_guid = $userid;
	$post->access_id = $access_id;
	
	$text = $text ?? '';
	$text = trim(str_replace('&nbsp;', ' ', $text));
	
	$limit = elgg_get_plugin_setting('limit', 'thewire');
	if ($limit > 0) {
		$text_for_size = elgg_strip_tags($text);
		if (elgg_strlen($text_for_size) > $limit) {
			return false;
		}
	}
	
	// no html tags allowed so we strip (except links (a) for mention support)
	$text = elgg_strip_tags($text, '<a>');
	
	// no html tags allowed so we escape
	$post->description = $text;

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
		'object_guid' => $post->guid,
		'subject_guid' => $post->owner_guid,
		'target_guid' => $post->container_guid,
	]);

	return $post->guid;
}

/**
 * Get an array of hashtags from a text string
 *
 * @param string $text The text of a post
 *
 * @return array
 *
 * @deprecated 6.2
 */
function thewire_get_hashtags(string $text): array {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.2');
	
	// beginning of text or white space followed by hashtag
	// the hashtag must begin with # and contain at least one character not digit, space, or punctuation
	$matches = [];
	preg_match_all('/(^|[^\w])#(\w+[^\s\d[:punct:]\x{2018}-\x{201F}]+\w*)/u', $text, $matches);
	
	return $matches[2] ?? [];
}

/**
 * Replace urls, hash tags, and @'s by links
 *
 * @param string $text The text of a post
 *
 * @return string
 *
 * @deprecated 6.2 use elgg_format_html()
 */
function thewire_filter(string $text): string {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_format_html().', '6.2');
	
	$text = ' ' . $text;

	// email addresses
	$text = elgg_parse_emails($text);

	// links
	$text = elgg_parse_urls($text);

	// usernames
	$text = elgg_parse_mentions($text);

	// hashtags
	$text = preg_replace_callback(
		'/(^|[^\w])#(\w+[^\s\d[:punct:]\x{2018}-\x{201F}]+\w*)/u',
		function ($matches) {
			$tag = elgg_extract(2, $matches);
			$url = elgg_generate_url('collection:object:thewire:tag', [
				'tag' => $tag,
			]);
			$link = elgg_view_url($url, "#{$tag}");
			
			return elgg_extract(1, $matches) . $link;
		},
		$text) ?? $text;

	return trim($text);
}
