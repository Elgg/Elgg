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
	'full_view' => false,
	'pagination' => false,
);

// show all posts for admin or users looking at their own widget
// show only published posts for other users.
$show_only_published = true;

if ($vars["entity"]->canEdit()) {
	$show_only_published = false;
}

if ($show_only_published) {
	$options['metadata_name_value_pairs'] = array(
		array('name' => 'status', 'value' => 'published')
	);
}

$content = elgg_list_entities_from_metadata($options);

echo $content;

if ($content) {
	$blog_url = "blog/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $blog_url,
		'text' => elgg_echo('blog:moreblogs'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('blog:noblogs');
}
