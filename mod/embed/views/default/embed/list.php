<?php

/**
 * View a list of embeddable items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page
 * @uses $vars['count']       Number of items in the complete list
 *
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */
$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof ElggGroup && $page_owner->isMember()) {
	$vars['base_url'] = 'embed?container_guid=' . $page_owner->guid;
} else {
	$vars['base_url'] = 'embed';
}

$vars['item_view'] = 'embed/item';
echo elgg_view('page/components/list', $vars);
