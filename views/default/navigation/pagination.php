<?php
/**
 * Elgg pagination
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses int    $vars['offset']       The offset in the list
 * @uses int    $vars['limit']        Number of items per page
 * @uses int    $vars['count']        Number of items in list
 * @uses string $vars['base_url']     Base URL to use in links
 * @uses string $vars['url_fragment'] URL fragment to add to links if not present in base_url (optional)
 * @uses string $vars['offset_key']   The string to use for offet in the URL
 */

if (elgg_in_context('widget')) {
	// widgets do not show pagination
	return;
}

$count = (int) elgg_extract('count', $vars, 0);
if (!$count) {
	return;
}

$offset = abs((int) elgg_extract('offset', $vars, 0));
// because you can say $vars['limit'] = 0
if (!$limit = (int) elgg_extract('limit', $vars, elgg_get_config('default_limit'))) {
	$limit = 10;
}

$offset_key = elgg_extract('offset_key', $vars, 'offset');
$url_fragment = elgg_extract('url_fragment', $vars, '');

// some views pass an empty string for base_url
if (isset($vars['base_url']) && $vars['base_url']) {
	$base_url = elgg_extract('base_url', $vars);
} elseif (elgg_is_xhr() && !empty($_SERVER['HTTP_REFERER'])) {
	$base_url = $_SERVER['HTTP_REFERER'];
} else {
	$base_url = current_page_url();
}

$base_url_has_fragment = preg_match('~#.~', $base_url);

$get_href = function ($offset) use ($base_url, $base_url_has_fragment, $offset_key, $url_fragment) {
	$link = elgg_http_add_url_query_elements($base_url, [$offset_key => $offset]);
	if (!$base_url_has_fragment && $offset) {
		$link .= "#$url_fragment";
	}
	return $link;
};

if ($count <= $limit && $offset == 0) {
	// no need for pagination
	return;
}

$total_pages = (int) ceil($count / $limit);
$current_page = (int) ceil($offset / $limit) + 1;

$pages = [];

// determine starting page
$start_page = max(min([$current_page - 2, $total_pages - 4]), 1);

// add previous
$prev_offset = $offset - $limit;
if ($prev_offset < 1) {
	// don't include offset=0
	$prev_offset = null;
}

$pages['prev'] = [
	'text' => elgg_echo('previous'),
	'href' => $get_href($prev_offset),
];

if ($current_page == 1) {
	$pages['prev']['disabled'] = true;
}

// add first page to be listed
if (1 < $start_page) {
	$pages[1] = [];
}

// added dotted spacer
if (1 < ($start_page - 2)) {
	$pages[] = ['text' => '...', 'disabled' => true];
} elseif ($start_page == 3) {
	$pages[2] = [];
}

$max = 1;
for ($page = $start_page; $page <= $total_pages; $page++) {
	if ($max > 5) {
		break;
	}
	$pages[$page] = [];
	$max++;
}

// added dotted spacer
if ($total_pages > ($start_page + 6)) {
	$pages[] = ['text' => '...', 'disabled' => true];
} elseif (($start_page + 5) == ($total_pages - 1)) {
	$pages[$total_pages - 1] = [];
}

// add last page to be listed
if ($total_pages >= ($start_page + 5)) {
	$pages[$total_pages] = [];
}

// add next
$next_offset = $offset + $limit;
if ($next_offset >= $count) {
	$next_offset--;
}

$pages['next'] = [
	'text' => elgg_echo('next'),
	'href' => $get_href($next_offset),
];

if ($current_page == $total_pages) {
	$pages['next']['disabled'] = true;
}

$list ="";
foreach ($pages as $page_num => $page) {
	if ($page_num == $current_page) {
		$list .= elgg_format_element('li', ['class' => 'elgg-state-selected'], "<span>$page_num</span>");
	} else {
		$href = elgg_extract('href', $page);
		$text = elgg_extract('text', $page, $page_num);
		$disabled = elgg_extract('disabled', $page, false);
		
		if (!$href && !$disabled) {
			$page_offset = (($page_num - $current_page) * $limit) + $offset;
			if ($page_offset <= 0) {
				// don't include offset=0
				$page_offset = null;
			}
			$href = $get_href($page_offset);
		}
		
		if ($href && !$disabled) {
			$link = elgg_view('output/url', [
				'href' => $href,
				'text' => $text,
				'is_trusted' => true,
			]);
		} else {
			$link = elgg_format_element('span', [], $page['text']);
		}
		
		$element_options = [];
		if ($disabled) {
			$element_options['class'] = 'elgg-state-disabled';
		}
			
		$list .= elgg_format_element('li', $element_options, $link);
	}
}

echo elgg_format_element('ul', ['class' => 'elgg-pagination'], $list);
