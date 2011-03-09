<?php
/**
 * Blog archives
 */

$loggedin_user = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();

// This is a limitation of the URL schema.
if ($page_owner && $vars['page'] != 'friends') {
	$dates = get_entity_dates('object', 'blog', $page_owner->getGUID());
	
	if ($dates) {
		$title = elgg_echo('blog:archives');
		$content = '<ul class="blog-archives">';
		foreach($dates as $date) {
			$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
			$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

			$link = elgg_get_site_url() . 'blog/archive/' . $page_owner->username . '/' . $timestamplow . '/' . $timestamphigh;
			$month = elgg_echo('date:month:' . substr($date, 4, 2), array(substr($date, 0, 4)));
			$content .= "<li><a href=\"$link\" title=\"$month\">$month</a></li>";
		}
		$content .= '</ul>';

		echo elgg_view_module('aside', $title, $content);
	}
}