<?php
/**
 * Blog tag cloud
 */

$loggedin_user = get_loggedin_user();
$page_owner = elgg_get_page_owner();

if ($page_owner && $vars['page'] != 'friends') {

	// friends page lists all tags; mine lists owner's
	$owner_guid = ($vars['page'] == 'friends') ? '' : $page_owner->getGUID();
	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'owner_guid' => $owner_guid,
		'threshold' => 0,
		'limit' => 50,
		'tag_name' => 'tags',
	);
	echo elgg_view_tagcloud($options);
} else {
	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'threshold' => 0,
		'limit' => 50,
		'tag_name' => 'tags',
	);
	echo elgg_view_tagcloud($options);
}
