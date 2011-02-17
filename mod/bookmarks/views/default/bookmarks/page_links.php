<?php
/**
 * Shows a "Bookmark this" link and icon
 */
$url = 'pg/bookmarks/add/' . elgg_get_logged_in_user_guid()
		. '?address=' . urlencode(current_page_url());
		$return[] = new ElggMenuItem('bookmark_this_page', elgg_echo('bookmarks:this'), $url);

echo elgg_view('output/url', array(
		'text' => '<span class="elgg-icon elgg-icon-bookmark"></span>',
		'href' => $url,
		'title' => $label,
		'rel' => 'nofollow',
		'encode_text' => false,
		'class' => 'right elgg-bookmark-page',
));
