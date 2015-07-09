<?php
/**
 * Blog archives
 */

$loggedin_user = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();
$page = elgg_extract('page', $vars);

if (!$page_owner) {
	return;
}

if ($page == 'friends') {
	return;
}

if (elgg_instanceof($page_owner, 'user')) {
	$url_segment = 'blog/archive/' . $page_owner->username;
} else {
	$url_segment = 'blog/group/' . $page_owner->getGUID() . '/archive';
}

$dates = get_entity_dates('object', 'blog', $page_owner->getGUID());
if (!$dates) {
	return;
}

$dates = array_reverse($dates);

$months = '';
foreach ($dates as $date) {
	$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
	$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

	$link = $url_segment . '/' . $timestamplow . '/' . $timestamphigh;
	$month = elgg_echo('date:month:' . substr($date, 4, 2), array(substr($date, 0, 4)));
	
	$link = elgg_view('output/url', [
		'href' => $link,
		'title' => $month,
		'text' => $month
	]);
	$months .= elgg_format_element('li', [], $link);
}

$content = elgg_format_element('ul', ['class' => 'blog-archives'], $months);

echo elgg_view_module('aside', elgg_echo('blog:archives'), $content);
