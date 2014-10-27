<?php

/**
 * Functions for Elgg's search system.
 * Contains functions and default plugin hook callbacks for common search types.
 * 
 * @package Elgg.Core
 * @subpackage Search
 */

/**
 * Find entities using one of the core search types ('object', 'group', 'user' or 'tags') or one of the custom search types
 * 
 * @param string $query       Query string to search for
 * @param string $search_type Search type
 * @param array  $params      An array of all options accepted by { @link elgg_get_entities } (except for 'order_by') plus:
 * 
 * highlight_matches    => whether query matches should be highlighted and stored as volatile date for each entity
 * sort                 => type of sorting to apply to matched results ('relevance', 'created', 'updated', 'action_on' or 'alpha'), 
 *                         Default sort type is 'relevance'. 'order_by' option, if passed, will always be overriden to respect 'sort' option
 * order                => asc or desc
 *
 * @param mixed  $default     Default value to return
 * @return type array An array in the form <code>array('entities' => $entities, 'count' => $count);</code>
 */
function elgg_search($query, $search_type, $params = array(), $default = null) {

	$params['query'] = sanitize_string(stripslashes($query));
	$params['search_type'] = $search_type;

	return elgg_trigger_plugin_hook('search', $search_type, $params, $default);
}

/**
 * Initialize the file library.
 * Listens to system init and configures the search system
 *
 * @return void
 * @access private
 */
function _elgg_search_init() {

	global $CONFIG;

	$CONFIG->search_info['min_chars'] = $CONFIG->ft_min_word_len;
	$CONFIG->search_info['max_chars'] = $CONFIG->ft_max_word_len;

	elgg_register_plugin_hook_handler('search', 'object', '_elgg_search_objects_hook');
	elgg_register_plugin_hook_handler('search', 'user', '_elgg_search_users_hook');
	elgg_register_plugin_hook_handler('search', 'group', '_elgg_search_groups_hook');
	elgg_register_plugin_hook_handler('search', 'tags', '_elgg_search_tags_hook');
}

/**
 * Checks if minimum and maximum lengths of words for MySQL search are defined and store them in Elgg data lists
 * 
 * @return void
 * @access private
 */
function _elgg_search_upgrade() {

	$result = false;
	try {
		$result = get_data_row('SELECT @@ft_min_word_len as min, @@ft_max_word_len as max');
	} catch (DatabaseException $e) {
		// some servers don't have these values set which leads to exception
		elgg_log($e->getMessage(), 'ERROR');
	}

	if ($result) {
		$min = $result->min;
		$max = $result->max;
	} else {
		// defaults from MySQL on Ubuntu Linux
		$min = 4;
		$max = 90;
	}

	elgg_save_config('ft_min_word_len', $min);
	elgg_save_config('ft_max_word_len', $max);
}

/**
 * Get objects that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @access private
 */
function _elgg_search_objects_hook($hook, $type, $value, $params) {

	$defaults = array(
		'wheres' => array(),
		'joins' => array(),
		'preload_owners' => true,
		'highlight_matches' => true,
	);

	$params = array_merge($defaults, $params);

	$db_prefix = elgg_get_config('dbprefix');

	if (!is_array($params['joins'])) {
		$params['joins'] = array($params['joins']);
	}
	$join = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
	array_unshift($params['joins'], $join);

	if (!is_array($params['wheres'])) {
		$params['wheres'] = array($params['wheres']);
	}
	$fields = array('title', 'description');
	$where = search_get_where_sql('oe', $fields, $params);
	array_unshift($params['wheres'], $where);

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'oe', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	if ($params['highlight_matches']) {
		// add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {
			$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
			$entity->setVolatileData('search_matched_title', $title);

			$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
			$entity->setVolatileData('search_matched_description', $desc);
		}
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get groups that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @access private
 */
function _elgg_search_groups_hook($hook, $type, $value, $params) {

	$defaults = array(
		'wheres' => array(),
		'joins' => array(),
		'preload_owners' => false,
		'highlight_matches' => true,
	);

	$params = array_merge($defaults, $params);

	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	if (!is_array($params['joins'])) {
		$params['joins'] = array($params['joins']);
	}

	$join = "JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid";
	array_unshift($params['joins'], $join);

	if (!is_array($params['wheres'])) {
		$params['wheres'] = array($params['wheres']);
	}
	$fields = array('name', 'description');
	$where = search_get_where_sql('ge', $fields, $params);
	array_unshift($params['wheres'], $where);

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ge', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	if ($params['highlight_matches']) {
		// add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {
			$name = search_get_highlighted_relevant_substrings($entity->name, $query);
			$entity->setVolatileData('search_matched_title', $name);

			$description = search_get_highlighted_relevant_substrings($entity->description, $query);
			$entity->setVolatileData('search_matched_description', $description);
		}
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get users that match the search parameters.
 *
 * Searches on username, display name, and profile fields
 * 
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @access private
 */
function _elgg_search_users_hook($hook, $type, $value, $params) {

	$defaults = array(
		'wheres' => array(),
		'joins' => array(),
		'preload_owners' => false,
		'highlight_matches' => true,
	);

	$params = array_merge($defaults, $params);

	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	if (!is_array($params['joins'])) {
		$params['joins'] = array($params['joins']);
	}

	if (!is_array($params['wheres'])) {
		$params['wheres'] = array($params['wheres']);
	}

	// username and display name
	$fields = array('username', 'name');
	$where = search_get_where_sql('ue', $fields, $params, FALSE);

	// profile fields
	$profile_fields = array_keys(elgg_get_config('profile_fields'));

	// get the where clauses for the md names
	// can't use egef_metadata() because the n_table join comes too late.
	$clauses = _elgg_entities_get_metastrings_options('metadata', array(
		'metadata_names' => $profile_fields,
	));

	$joins = array(
		"JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid",
		"JOIN {$db_prefix}metastrings msv ON n_table.value_id = msv.id"
	);
	$joins = array_merge($clauses['joins'], $joins);
	$params['joins'] = array_merge($joins, $params['joins']);
	
	// no fulltext index, can't disable fulltext search in this function.
	// $md_where .= " AND " . search_get_where_sql('msv', array('string'), $params, FALSE);
	$md_where = "(({$clauses['wheres'][0]}) AND msv.string LIKE '%$query%')";
	$params['wheres'][] = "(($where) OR ($md_where))";

	$params['count'] = true;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ue', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	if ($params['highlight_matches']) {
		// add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {

			$title = search_get_highlighted_relevant_substrings($entity->name, $query);

			// include the username if it matches but the display name doesn't.
			if (false !== strpos($entity->username, $query)) {
				$username = search_get_highlighted_relevant_substrings($entity->username, $query);
				$title .= " ($username)";
			}

			$entity->setVolatileData('search_matched_title', $title);

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

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get entities with tags that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 * @access private
 */
function _elgg_search_tags_hook($hook, $type, $value, $params) {

	$defaults = array(
		'wheres' => array(),
		'joins' => array(),
		'preload_owners' => true,
		'highlight_matches' => true,
	);

	$params = array_merge($defaults, $params);

	if (!is_array($params['joins'])) {
		$params['joins'] = array($params['joins']);
	}

	if (!is_array($params['wheres'])) {
		$params['wheres'] = array($params['wheres']);
	}


	$db_prefix = elgg_get_config('dbprefix');

	$valid_tag_names = elgg_get_registered_tag_metadata_names();

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);

	// if passed a tag metadata name, only search on that tag name.
	// tag_name isn't included in the params because it's specific to
	// tag searches.
	if ($tag_names = elgg_extract('tag_names', $params, get_input('tag_names'))) {
		if (is_array($tag_names)) {
			$search_tag_names = $tag_names;
		} else {
			$search_tag_names = array($tag_names);
		}

		// check these are valid to avoid arbitrary metadata searches.
		foreach ($search_tag_names as $i => $tag_name) {
			if (!in_array($tag_name, $valid_tag_names)) {
				unset($search_tag_names[$i]);
			}
		}
	} else {
		$search_tag_names = $valid_tag_names;
	}

	if (!$search_tag_names) {
		return array('entities' => array(), 'count' => 0);
	}

	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";

	$access = _elgg_get_access_where_sql(array('table_alias' => 'md'));
	$sanitised_tags = array();

	foreach ($search_tag_names as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}

	$tags_in = implode(',', $sanitised_tags);

	$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' AND $access)";

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	if ($params['highlight_matches']) {
		// add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {
			$matched_tags_strs = array();

			// get tags for each tag name requested to find which ones matched.
			foreach ($search_tag_names as $tag_name) {
				$tags = $entity->getTags($tag_name);

				// @todo make one long tag string and run this through the highlight
				// function.  This might be confusing as it could chop off
				// the tag labels.
				if (in_array(strtolower($query), array_map('strtolower', $tags))) {
					if (is_array($tags)) {
						$tag_name_str = elgg_echo("tag_names:$tag_name");
						$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
					}
				}
			}

			// different entities have different titles
			switch ($entity->type) {
				case 'site':
				case 'user':
				case 'group':
					$title_tmp = $entity->name;
					break;

				case 'object':
					$title_tmp = $entity->title;
					break;
			}

			// Nick told me my idea was dirty, so I'm hard coding the numbers.
			$title_tmp = strip_tags($title_tmp);
			if (elgg_strlen($title_tmp) > 297) {
				$title_str = elgg_substr($title_tmp, 0, 297) . '...';
			} else {
				$title_str = $title_tmp;
			}

			$desc_tmp = strip_tags($entity->description);
			if (elgg_strlen($desc_tmp) > 297) {
				$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
			} else {
				$desc_str = $desc_tmp;
			}

			$tags_str = implode('. ', $matched_tags_strs);
			$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query'], 30, 300, true);

			$entity->setVolatileData('search_matched_title', $title_str);
			$entity->setVolatileData('search_matched_description', $desc_str);
			$entity->setVolatileData('search_matched_extra', $tags_str);
		}
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
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
		$return = search_highlight_words($words, $haystack);

		return $return;
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
			while (FALSE !== $pos = elgg_strpos($haystack_lc, $word, $offset)) {
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
	$last_pos = $starts[count($starts) - 1];
	if ($last_pos + elgg_strlen($return_strs[$last_pos]) < $haystack_length) {
		$return .= '...';
	}

	$return = search_highlight_words($words, $return);

	return $return;
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
	for ($i = 0; $i < $count; $i++) {
		$offset = $offsets[$i];
		$length = $lengths[$i];
		$end_pos = $offset + $length;

		// find the next entry that doesn't overlap
		while (array_key_exists($i + 1, $offsets) && $end_pos > $offsets[$i + 1]) {
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
	global $CONFIG;

	// don't worry about "s or boolean operators
	//$query = str_replace(array('"', '-', '+', '~'), '', stripslashes(strip_tags($query)));
	$query = stripslashes(strip_tags($query));

	$words = explode(' ', $query);

	$min_chars = $CONFIG->search_info['min_chars'];
	// if > ft_min_word we're not running in literal mode.
	if (elgg_strlen($query) >= $min_chars) {
		// clean out any words that are ignored by mysql
		foreach ($words as $i => $word) {
			if (elgg_strlen($word) < $min_chars) {
				unset($words[$i]);
			}
		}
	}

	if ($format == 'string') {
		return implode(' ', $words);
	}

	return $words;
}

/**
 * Returns a where clause for a search query.
 *
 * @param str $table Prefix for table to search on
 * @param array $fields Fields to match against
 * @param array $params Original search params
 * @return str
 */
function search_get_where_sql($table, $fields, $params, $use_fulltext = TRUE) {
	global $CONFIG;
	$query = $params['query'];

	// add the table prefix to the fields
	foreach ($fields as $i => $field) {
		if ($table) {
			$fields[$i] = "$table.$field";
		}
	}

	$where = '';

	// if query is shorter than the min for fts words
	// it's likely a single acronym or similar
	// switch to literal mode
	if (elgg_strlen($query) < $CONFIG->search_info['min_chars']) {
		$likes = array();
		$query = sanitise_string($query);
		foreach ($fields as $field) {
			$likes[] = "$field LIKE '%$query%'";
		}
		$likes_str = implode(' OR ', $likes);
		$where = "($likes_str)";
	} else {
		// if we're not using full text, rewrite the query for bool mode.
		// exploiting a feature(ish) of bool mode where +-word is the same as -word
		if (!$use_fulltext) {
			$query = '+' . str_replace(' ', ' +', $query);
		}

		// if using advanced, boolean operators, or paired "s, switch into boolean mode
		$booleans_used = preg_match("/([\-\+~])([\w]+)/i", $query);
		$advanced_search = (isset($params['advanced_search']) && $params['advanced_search']);
		$quotes_used = (elgg_substr_count($query, '"') >= 2);

		if (!$use_fulltext || $booleans_used || $advanced_search || $quotes_used) {
			$options = 'IN BOOLEAN MODE';
		} else {
			// natural language mode is default and this keyword isn't supported in < 5.1
			//$options = 'IN NATURAL LANGUAGE MODE';
			$options = '';
		}

		// if short query, use query expansion.
		// @todo doesn't seem to be working well.
//		if (elgg_strlen($query) < 5) {
//			$options .= ' WITH QUERY EXPANSION';
//		}
		$query = sanitise_string($query);

		$fields_str = implode(',', $fields);
		$where = "(MATCH ($fields_str) AGAINST ('$query' $options))";
	}

	return $where;
}

/**
 * Returns ORDER BY sql for insertion into elgg_get_entities().
 *
 * @param str $entities_table Prefix for entities table.
 * @param str $type_table Prefix for the type table.
 * @param str $sort ORDER BY part
 * @param str $order ASC or DESC
 * @return str
 */
function search_get_order_by_sql($entities_table, $type_table, $sort, $order) {

	$on = NULL;

	switch ($sort) {
		default:
		case 'relevance':
			// default is relevance descending.
			// ascending relevancy is silly and complicated.
			$on = '';
			break;
		case 'created':
			$on = "$entities_table.time_created";
			break;
		case 'updated':
			$on = "$entities_table.time_updated";
			break;
		case 'action_on':
			// @todo not supported yet in core
			$on = '';
			break;
		case 'alpha':
			// @todo not support yet because both title
			// and name columns are used for this depending
			// on the entity, which we don't always know.  >:O
			break;
	}
	$order = strtolower($order);
	if ($order != 'asc' && $order != 'desc') {
		$order = 'DESC';
	}

	if ($on) {
		$order_by = "$on $order";
	} else {
		$order_by = '';
	}

	return $order_by;
}

elgg_register_event_handler('init', 'system', '_elgg_search_init');
elgg_register_event_handler('upgrade', 'system', '_elgg_search_upgrade');
