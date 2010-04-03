<?php

//get the num of blog entries the user wants to display
$num = $vars['entity']->num_display;

//if no number has been set, default to 4
if (!$num) {
	$num = 4;
}

$context = get_context();
set_context('search');
$content = elgg_list_entities(array('types' => 'object', 'subtypes' => 'blog', 'container_guid' => $vars['entity']->owner_guid, 'limit' => $num, 'full_view' => FALSE, 'pagination' => FALSE));
set_context($context);

echo $content;

if ($content) {
	$blogurl = $vars['url'] . "pg/blog/" . page_owner_entity()->username;
	echo "<div class=\"shares_widget_wrapper\"><a href=\"{$blogurl}\">".elgg_echo('blog:moreblogs')."</a></div>";
}