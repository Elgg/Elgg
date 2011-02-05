<?php
/**
 * Blog archives
 */

$loggedin_user = get_loggedin_user();
$page_owner = elgg_get_page_owner_entity();

// This is a limitation of the URL schema.
if ($page_owner && $vars['page'] != 'friends') {
	$dates = blog_get_blog_months($user);

	if ($dates) {
		$title = elgg_echo('blog:archives');
		$content = '<ul class="blog-archives">';
		foreach($dates as $date) {
			$date = $date->yearmonth;

			$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
			$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

			$link = elgg_get_site_url() . 'pg/blog/archive/' . $page_owner->username . '/' . $timestamplow . '/' . $timestamphigh;
			$month = elgg_echo('date:month:' . substr($date, 4, 2), array(substr($date, 0, 4)));
			$content .= "<li><a href=\"$link\" title=\"$month\">$month</a></li>";
		}
		$content .= '</ul>';

		echo elgg_view('layout/objects/module', array(
			'title' => $title,
			'body' => $content,
			'class' => 'elgg-module-aside',
		));
	}
}