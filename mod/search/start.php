<?php

/**
 * Elgg search plugin
 */
elgg_register_event_handler('init', 'system', 'search_init');

/**
 * Initialize search plugin
 */
function search_init() {

	// page handler for search actions and results
	elgg_register_page_handler('search', 'search_page_handler');

	// exclude /search routes from indexing
	elgg_register_plugin_hook_handler('robots.txt', 'site', 'search_exclude_robots');

	// add in CSS for search elements
	elgg_extend_view('elgg.css', 'search/search.css');

	elgg_register_plugin_hook_handler('search:format', 'entity', 'search_format_comment_entity');
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

	return elgg_ok_response(elgg_view_resource('search/index'));
}

/**
 * Prepare search params from request query elements
 * @return array
 */
function search_prepare_search_params() {

	$partial_match = true;

	// $search_type == all || entities || trigger plugin hook
	$search_type = get_input('search_type', 'all');
	if ($search_type == 'tags') {
		elgg_deprecated_notice('"tags" search type has been deprecated. By default, "entities" search performs search within registered tags.', '3.0');
		$search_type = 'entities';
		$partial_match = false;
		$fields = get_input('tag_names');
		if (!$fields) {
			$fields = (array) elgg_get_registered_tag_metadata_names();
		}
	} else {
		$partial_match = true;
		$fields = get_input('fields');
	}

	$query = get_input('q', get_input('tag', ''));

	if (preg_match('/\"(.*)\"/i', $query)) {
		// if query is quoted, e.g. "elgg has been released", perform literal search
		$tokenize = false;
		$query = preg_replace('/\"(.*)\"/i', '$1', $query);
	} else {
		$tokenize = true;
	}

	// @todo there is a bug in get_input that makes variables have slashes sometimes.
	// @todo is there an example query to demonstrate ^
	// XSS protection is more important that searching for HTML.
	$query = stripslashes($query);

	if ($search_type == 'all') {
		// We only display 2 results per search type
		$limit = 2;
		$offset = 0;
		$pagination = false;
	} else {
		$limit = max((int) get_input('limit'), elgg_get_config('default_limit'));
		$offset = get_input('offset', 0);
		$pagination = true;
	}

	$entity_type = get_input('entity_type', ELGG_ENTITIES_ANY_VALUE);
	if ($entity_type) {
		$entity_subtype = get_input('entity_subtype', ELGG_ENTITIES_ANY_VALUE);
	} else {
		$entity_subtype = ELGG_ENTITIES_ANY_VALUE;
	}

	$owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE);
	$container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);

	$sort = get_input('sort');
	$order = get_input('order', '');

	switch ($sort) {
		case 'action_on' :
			$sort = 'last_action';
			break;

		case 'created' :
			$sort = 'time_created';
			break;

		case 'updated' :
			$sort = 'time_updated';
			break;

		case 'alpha' :
			if (!$order || !in_array(strtoupper($order), ['ASC', 'DESC'])) {
				$order = 'ASC';
			}
			$sort = 'name';
			break;
	}

	$params = array(
		'query' => $query,
		'offset' => $offset,
		'limit' => $limit,
		'sort' => $sort,
		'order' => $order,
		'search_type' => $search_type,
		'fields' => $fields,
		'partial_match' => $partial_match,
		'tokenize' => $tokenize,
		'type' => $entity_type,
		'subtype' => $entity_subtype,
		'owner_guid' => $owner_guid,
		'container_guid' => $container_guid,
		'pagination' => $pagination,
	);

	return $params;
}

/**
 * Returns searchable type/subtype pairs
 *
 * <code>
 * [
 *    'user' => [],
 *    'object' => [
 *       'blog',
 *    ]
 * ]
 * </code>
 *
 * @param array $params Search params
 * @return array
 */
function search_get_type_subtype_pairs(array $params = []) {
	$type_subtype_pairs = get_registered_entity_types();

	if (_elgg_services()->hooks->hasHandler('search_types', 'get_queries')) {
		elgg_deprecated_notice("
			'search_types','get_queries' hook has been deprecated.
			Use 'search:config','type_subtype_pairs' hook.
			", '3.0');
		$type_subtype_pairs = elgg_trigger_plugin_hook('search_types', 'get_queries', $params, $type_subtype_pairs);
	}

	return elgg_trigger_plugin_hook('search:config', 'type_subtype_pairs', $params, $type_subtype_pairs);
}

/**
 * Returns search types
 *
 * @param array $params Search params
 * @return array
 */
function search_get_search_types(array $params = []) {
	$search_types = [];

	if (_elgg_services()->hooks->hasHandler('search_types', 'get_types')) {
		elgg_deprecated_notice("
			'search_types','get_types' hook has been deprecated.
			Use 'search:config','search_types' hook.
			", '3.0');
		$search_types = elgg_trigger_plugin_hook('search_types', 'get_types', $params, $search_types);
	}

	return elgg_trigger_plugin_hook('search:config', 'search_types', $params, $search_types);
}

/**
 * Populate search-related volatile data
 *
 * @param \ElggEntity $entity Found entity
 * @param array       $params Search params
 * @return void
 */
function search_prepare_entity_view(\ElggEntity $entity, array $params = []) {

	$query = elgg_extract('query', $params);

	$type = $entity->getType();
	$subtype = $entity->getSubtype();
	$container = $entity->getContainerEntity();
	$owner = $entity->getOwnerEntity();

	$tokenize = elgg_extract('tokenize', $params, true);

	if (!$entity->getVolatileData('search_matched_title')) {
		$title = search_get_highlighted_relevant_substrings($entity->getDisplayName(), $query, 1, 300, !$tokenize);
		$entity->setVolatileData('search_matched_title', $title);
	}

	if (!$entity->getVolatileData('search_matched_description')) {
		$desc = search_get_highlighted_relevant_substrings($entity->description, $query, 10, 300, !$tokenize);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	if (!$entity->getVolatileData('search_matched_extra')) {

		$fields = elgg_trigger_plugin_hook('search:fields', "$type", $params, []);
		if ($subtype) {
			$fields = elgg_trigger_plugin_hook('search:fields', "$type:$subtype", $params, $fields);
		}

		if (!isset($params['fields'])) {
			$params['fields'] = $fields;
		} else {
			// only allow known fields
			$params['fields'] = array_intersect($fields, $params['fields']);
		}

		$fields = elgg_extract('fields', $params);

		switch ($type) {
			case 'user' :
				$prefix = 'profile';
				$exclude = ['name', 'description'];
				break;
			case 'group' :
				$prefix = 'group';
				$exclude = ['name', 'description'];
				break;
			case 'object' :
				$exclude = ['title', 'description'];
				$prefix = 'tag_names';
				break;
		}

		$matches = array();
		foreach ($fields as $field) {
			if (in_array($field, $exclude)) {
				continue;
			}
			$metadata = $entity->$field;
			if (is_array($metadata)) {
				foreach ($metadata as $text) {
					if (stristr($text, $query)) {
						$matches[$field][] = search_get_highlighted_relevant_substrings($text, $query, 1, 300, !$tokenize);
					}
				}
			} else {
				if (stristr($metadata, $query)) {
					$matches[$field][] = search_get_highlighted_relevant_substrings($metadata, $query, 1, 300, !$tokenize);
				}
			}
		}

		$extra = array();
		foreach ($matches as $field => $match) {
			$keys = [
				"$type:$subtype:field:$field",
				"$type:$field",
				"$prefix:$field",
			];

			foreach ($keys as $key) {
				if (elgg_language_key_exists($key)) {
					$label = elgg_echo($key);
					break;
				}
			}

			if (!$label) {
				$label = elgg_echo("tag_names:$field");
			}

			$label = elgg_format_element('strong', [
				'class' => 'search-match-extra-label',
					], elgg_echo($label));




			$extra_row = elgg_format_element('p', [
				'class' => 'elgg-output',
					], $label . ': ' . implode(', ', $match));

			$extra[] = $extra_row;
		}

		$entity->setVolatileData('search_matched_extra', implode('', $extra));
	}

	if (!$entity->getVolatileData('search_icon')) {
		$size = elgg_extract('size', $params, 'small');

		if ($entity->hasIcon($size) || $entity instanceof ElggFile) {
			$icon = elgg_view_entity_icon($entity, $size);
		} else if ($type == 'user' || $type == 'group') {
			$icon = elgg_view_entity_icon($entity, $size);
		} elseif ($owner instanceof ElggUser) {
			$icon = elgg_view_entity_icon($owner, $size);
		} else if ($container instanceof ElggUser) {
			// display a generic icon if no owner, though there will probably be
			// other problems if the owner can't be found.
			$icon = elgg_view_entity_icon($entity, $size);
		}
		$entity->setVolatileData('search_icon', $icon);
	}

	if (!$entity->getVolatileData('search_url')) {
		$url = $entity->getURL();
		$entity->setVolatileData('search_url', $url);
	}

	if (!$entity->getVolatileData('search_time')) {
		$entity->setVolatileData('search_time', $entity->time_created);
	}

	return elgg_trigger_plugin_hook('search:format', 'entity', $params, $entity);
}

/**
 * Return a string with highlighted matched queries and relevant context
 * Determines context based upon occurrence and distance of words with each other.
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
	$last_pos = $starts[count($starts) - 1];
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
 * Returns the name of the view to render an entity in search results,
 *
 * @params array $param     Result parameters
 *                          - type: entity type
 *                          - subtype: entity subtype
 *                          - search_type: 'entities'|'tags'
 * @param string $view_type Since 3.0, only supports 'entity'
 * @return string
 */
function search_get_search_view(array $params = [], $view_type = 'entity') {

	if ($view_type != 'entity') {
		elgg_deprecated_notice(__FUNCTION__ . ' no longer supports "list" and "layout" view types.
			All lists and layouts are rendered in a standard way.', '3.0');
		switch ($view_type) {
			case 'list' :
				return 'search/list';

			case 'layout' :
				return 'page/layouts/default';
		}
	}

	$type = elgg_extract('type', $params);
	$subtype = elgg_extract('subtype', $params);
	$search_type = elgg_extract('search_type', $params);

	$views = [
		"search/$search_type/$type/$subtype",
		"search/$search_type/$type/default",
		"search/$type/$subtype/entity", // BC
		"search/$type/entity", // BC
		"search/$type/$subtype",
		"search/$type/default",
		"search/$search_type/entity", // BC
	];

	foreach ($views as $view) {
		if (elgg_view_exists($view)) {
			return $view;
		}
	}
}

/**
 * Exclude robots from indexing search pages
 *
 * This is good for performance since search is slow and there are many pages all
 * with the same content.
 *
 * @elgg_plugin_hook robots.txt search
 * 
 * @param \Elgg\Hook $hook Hook
 * @return string
 */
function search_exclude_robots(\Elgg\Hook $hook) {
	$rules = [
		'',
		'User-agent: *',
		'Disallow: /search/',
		''
	];

	$text = $hook->getValue();
	$text .= implode(PHP_EOL, $rules);
	return $text;
}

/**
 * Format comment entity in search results
 * 
 * @elgg_plugin_hook search:format entity
 * 
 * @param \Elgg\Hook $hook Hook
 * @return \ElggEntity
 */
function search_format_comment_entity(\Elgg\Hook $hook) {

	$entity = $hook->getValue();
	
	if (!elgg_instanceof($entity, 'object', 'comment')) {
		return;
	}

	$owner = $entity->getOwnerEntity();
	$size = elgg_extract('size', $vars, 'small');
	$icon = elgg_view_entity_icon($owner, $size);

	$container = $entity->getContainerEntity();

	if ($container->getType() == 'object') {
		$title = $container->title;
	} else {
		$title = $container->name;
	}

	if (!$title) {
		$title = elgg_echo('item:' . $container->getType() . ':' . $container->getSubtype());
	}

	if (!$title) {
		$title = elgg_echo('item:' . $container->getType());
	}

	$title = elgg_echo('search:comment_on', array($title));

	$entity->setVolatileData('search_matched_title', $title);

	return $entity;
}
