<?php
		
$num = $vars['entity']->num_display;
if (!$num) {
	$num = 4;
}

$content = elgg_list_entities(array('types' => 'object', 'subtypes' => 'thewire', 'container_guid' => $vars['entity']->owner_guid, 'limit' => $num, 'full_view' => FALSE, 'pagination' => FALSE));

echo $content;

if ($content) {
	$blogurl = elgg_get_site_url() . "pg/thewire/" . elgg_get_page_owner()->username;
	echo "<div class=\"shares_widget_wrapper\"><a href=\"{$blogurl}\">".elgg_echo('thewire:moreposts')."</a></div>";
}
