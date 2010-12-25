<?php
/**
 * User blog widget display view
 */

$num = $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$blog_url = "pg/blog/owner/" . elgg_get_page_owner()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $blog_url,
		'text' => elgg_echo('blog:moreblogs'),
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('blog:noblogs');
}
