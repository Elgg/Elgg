<?php
/**
 * Elgg search plugin
 */

elgg_register_event_handler('init','system','search_init');

/**
 * Initialize search plugin
 */
function search_init() {
	// page handler for search actions and results
	elgg_register_page_handler('search', 'search_page_handler');

	// add in CSS for search elements
	elgg_extend_view('elgg.css', 'search/css');

	// extend view for elgg topbar search box
	elgg_extend_view('page/elements/sidebar', 'search/header', 0);
	
	// register search result highlighting
	elgg_register_plugin_hook_handler('format', 'search', 'search_format_search_results');
}

/**
 * Page handler for search
 *
 * @param array $page Page elements from core page handler
 * @return bool
 */
function search_page_handler($page) {

	if (!get_input('q', null)) {
		set_input('q', $page[0]);
	}

	echo elgg_view_resource('search/index');
	return true;
}

/**
 * Adds extra volatile data to search results
 *
 * @param string      $hook   Hook name
 * @param string      $type   Hook type
 * @param \ElggEntity $entity Entity
 * @param array       $params Search parameters
 *
 * @return void
 */
function search_format_search_results($hook, $type, $entity, $params) {
	$query = elgg_extract('query', $params);
	
	if ($params['search_type'] == 'tags') {
		$matched_tags_strs = [];
		
		// get tags for each tag name requested to find which ones matched.
		foreach ($entity->getVolatileData('search_tag_names') as $tag_name) {
			$tags = $entity->getTags($tag_name);

			// @todo make one long tag string and run this through the highlight
			// function.  This might be confusing as it could chop off
			// the tag labels.
			if (in_array(strtolower(sanitize_string($query)), array_map('strtolower', $tags))) {
				if (is_array($tags)) {
					$tag_name_str = elgg_echo("tag_names:$tag_name");
					$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
				}
			}
		}

		$title_str = elgg_get_excerpt($entity->getDisplayName());
		$entity->setVolatileData('search_matched_title', $title_str);
		
		$desc_str  = elgg_get_excerpt($entity->description);
		$entity->setVolatileData('search_matched_description', $desc_str);
		
		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $query, 30, 300, true);
		$entity->setVolatileData('search_matched_extra', $tags_str);
		
		return;
	}
		
	if ($entity instanceof \ElggObject) {
			
		$title = search_get_highlighted_relevant_substrings($entity->title, $query);
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $desc);
		
	} elseif ($entity instanceof \ElggGroup) {
		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $description);
		
	} elseif ($entity instanceof \ElggUser) {
		$title = search_get_highlighted_relevant_substrings($entity->name, $query);
	
		// include the username if it matches but the display name doesn't.
		if (false !== strpos($entity->username, $query)) {
			$username = search_get_highlighted_relevant_substrings($entity->username, $query);
			$title .= " ($username)";
		}

		$entity->setVolatileData('search_matched_title', $title);

		$profile_fields = array_keys(elgg_get_config('profile_fields'));
		if (!empty($profile_fields)) {
			$matched = '';
			foreach ($profile_fields as $md_name) {
				$metadata = $entity->$md_name;
				if (is_array($metadata)) {
					foreach ($metadata as $text) {
						if (stristr($text, $query)) {
							$matched .= elgg_echo("profile:{$md_name}") . ': '
									. search_get_highlighted_relevant_substrings($text, $query);
						}
					}
				} else {
					if (stristr($metadata, $query)) {
						$matched .= elgg_echo("profile:{$md_name}") . ': '
								. search_get_highlighted_relevant_substrings($metadata, $query);
					}
				}
			}
	
			$entity->setVolatileData('search_matched_description', $matched);
		}
	}
}

/**
 * Return a string with highlighted matched queries and relevant context
 * Determines context based upon occurance and distance of words with each other.
 *
 * @param string $haystack
 * @param string $query
 * @param int $min_match_context = 30
 * @param int $max_length = 300
 * @param bool $tag_match Search is for tags. Don't ignore words.
 * @return string
 */
function search_get_highlighted_relevant_substrings($haystack, $query, $min_match_context = 30, $max_length = 300, $tag_match = false) {

	$haystack = strip_tags($haystack);
	$haystack_length = elgg_strlen($haystack);
	$haystack_lc = elgg_strtolower($haystack);

	if (!$tag_match) {
		$words = search_remove_ignored_words($query, 'array');
	} else {
		$words = array();
	}

	// if haystack < $max_length return the entire haystack w/formatting immediately
	if ($haystack_length <= $max_length) {
		return search_highlight_words($words, $haystack);
	}

	// get the starting positions and lengths for all matching words
	$starts = array();
	$lengths = array();
	foreach ($words as $word) {
		$word = elgg_strtolower($word);
		$count = elgg_substr_count($haystack_lc, $word);
		$word_len = elgg_strlen($word);
		$haystack_len = elgg_strlen($haystack_lc);

		// find the start positions for the words
		if ($count > 1) {
			$offset = 0;
			while (false !== $pos = elgg_strpos($haystack_lc, $word, $offset)) {
				$start = ($pos - $min_match_context > 0) ? $pos - $min_match_context : 0;
				$starts[] = $start;
				$stop = $pos + $word_len + $min_match_context;
				$lengths[] = $stop - $start;
				$offset += $pos + $word_len;

				if ($offset >= $haystack_len) {
					break;
				}
			}
		} else {
			$pos = elgg_strpos($haystack_lc, $word);
			$start = ($pos - $min_match_context > 0) ? $pos - $min_match_context : 0;
			$starts[] = $start;
			$stop = $pos + $word_len + $min_match_context;
			$lengths[] = $stop - $start;
		}
	}

	$offsets = search_consolidate_substrings($starts, $lengths);

	// figure out if we can adjust the offsets and lengths
	// in order to return more context
	$total_length = array_sum($offsets);

	$add_length = 0;
	if ($total_length < $max_length && $offsets) {
		$add_length = floor((($max_length - $total_length) / count($offsets)) / 2);

		$starts = array();
		$lengths = array();
		foreach ($offsets as $offset => $length) {
			$start = ($offset - $add_length > 0) ? $offset - $add_length : 0;
			$length = $length + $add_length;
			$starts[] = $start;
			$lengths[] = $length;
		}

		$offsets = search_consolidate_substrings($starts, $lengths);
	}

	// sort by order of string size descending (which is roughly
	// the proximity of matched terms) so we can keep the
	// substrings with terms closest together and discard
	// the others as needed to fit within $max_length.
	arsort($offsets);

	$return_strs = array();
	$total_length = 0;
	foreach ($offsets as $start => $length) {
		$string = trim(elgg_substr($haystack, $start, $length));

		// continue past if adding this substring exceeds max length
		if ($total_length + $length > $max_length) {
			continue;
		}

		$total_length += $length;
		$return_strs[$start] = $string;
	}

	// put the strings in order of occurence
	ksort($return_strs);

	// add ...s where needed
	$return = implode('...', $return_strs);
	if (!array_key_exists(0, $return_strs)) {
		$return = "...$return";
	}

	// add to end of string if last substring doesn't hit the end.
	$starts = array_keys($return_strs);
	$last_pos = $starts[count($starts)-1];
	if ($last_pos + elgg_strlen($return_strs[$last_pos]) < $haystack_length) {
		$return .= '...';
	}

	return search_highlight_words($words, $return);
}

/**
 * Takes an array of offsets and lengths and consolidates any
 * overlapping entries, returning an array of new offsets and lengths
 *
 * Offsets and lengths are specified in separate arrays because of possible
 * index collisions with the offsets.
 *
 * @param array $offsets
 * @param array $lengths
 * @return array
 */
function search_consolidate_substrings($offsets, $lengths) {
	// sort offsets by occurence
	asort($offsets, SORT_NUMERIC);

	// reset the indexes maintaining association with the original offsets.
	$offsets = array_merge($offsets);

	$new_lengths = array();
	foreach ($offsets as $i => $offset) {
		$new_lengths[] = $lengths[$i];
	}

	$lengths = $new_lengths;

	$return = array();
	$count = count($offsets);
	for ($i=0; $i<$count; $i++) {
		$offset = $offsets[$i];
		$length = $lengths[$i];
		$end_pos = $offset + $length;

		// find the next entry that doesn't overlap
		while (array_key_exists($i+1, $offsets) && $end_pos > $offsets[$i+1]) {
			$i++;
			if (!array_key_exists($i, $offsets)) {
				break;
			}
			$end_pos = $lengths[$i] + $offsets[$i];
		}

		$length = $end_pos - $offset;

		// will never have a colliding offset, so can return as a single array
		$return[$offset] = $length;
	}

	return $return;
}

/**
 * Safely highlights the words in $words found in $string avoiding recursion
 *
 * @param array $words
 * @param string $string
 * @return string
 */
function search_highlight_words($words, $string) {
	$i = 1;
	$replace_html = array(
		'strong' => rand(10000, 99999),
		'class' => rand(10000, 99999),
		'search-highlight' => rand(10000, 99999),
		'search-highlight-color' => rand(10000, 99999)
	);

	foreach ($words as $word) {
		// remove any boolean mode operators
		$word = preg_replace("/([\-\+~])([\w]+)/i", '$2', $word);
		
		// escape the delimiter and any other regexp special chars
		$word = preg_quote($word, '/');
		
		$search = "/($word)/i";

		// @todo
		// must replace with placeholders in case one of the search terms is
		// in the html string.
		// later, will replace the placeholders with the actual html.
		// Yeah this is hacky.  I'm tired.
		$strong = $replace_html['strong'];
		$class = $replace_html['class'];
		$highlight = $replace_html['search-highlight'];
		$color = $replace_html['search-highlight-color'];

		$replace = "<$strong $class=\"$highlight $color{$i}\">$1</$strong>";
		$string = preg_replace($search, $replace, $string);
		$i++;
	}

	foreach ($replace_html as $replace => $search) {
		$string = str_replace($search, $replace, $string);
	}

	return $string;
}

/**
 * Returns a query with stop and too short words removed.
 * (Unless the entire query is < ft_min_word_chars, in which case
 * it's taken literally.)
 *
 * @param array $query
 * @param str $format Return as an array or a string
 * @return mixed
 */
function search_remove_ignored_words($query, $format = 'array') {

	// don't worry about "s or boolean operators
	//$query = str_replace(array('"', '-', '+', '~'), '', stripslashes(strip_tags($query)));
	$query = stripslashes(strip_tags($query));
	$query = trim($query);
	
	$words = preg_split('/\s+/', $query);

	if ($format == 'string') {
		return implode(' ', $words);
	}

	return $words;
}

/**
 * Passes results, and original params to the view functions for
 * search type.
 *
 * @param array $results
 * @param array $params
 * @param string $view_type = list, entity or layout
 * @return string
 */
function search_get_search_view($params, $view_type) {
	
	if (!in_array($view_type, ['list', 'entity', 'layout'])) {
		return false;
	}

	$view_order = [];

	// check if there's a special search list view for this type:subtype
	if (isset($params['type']) && $params['type'] && isset($params['subtype']) && $params['subtype']) {
		$view_order[] = "search/{$params['type']}/{$params['subtype']}/$view_type";
	}

	// also check for the default type
	if (isset($params['type']) && $params['type']) {
		$view_order[] = "search/{$params['type']}/$view_type";
	}

	// check search types
	if (isset($params['search_type']) && $params['search_type']) {
		$view_order[] = "search/{$params['search_type']}/$view_type";
	}

	// finally default to a search list default
	$view_order[] = "search/$view_type";

	foreach ($view_order as $view) {
		if (elgg_view_exists($view)) {
			return $view;
		}
	}
	
	return false;
}
