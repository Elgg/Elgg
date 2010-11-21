<?php

$num_items = $vars['entity']->num_items;
if (!isset($num_items)) {
	$num_items = 30;
}

$options = array(
	'owner_guid' => elgg_get_page_owner_guid(),
	'threshold' => 1,
	'limit' => $num_items,
	'tag_name' => 'tags',
);
echo elgg_view_tagcloud($options);
