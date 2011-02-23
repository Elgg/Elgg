<?php
/**
 * Elgg user display
 *
 * @package Elgg
 * @subpackage Core
 */

$user = $vars['entity'];

$icon = elgg_view('profile/icon', array('entity' => $user, 'size' => 'tiny'));

// Simple XFN
$rel = '';
if (elgg_get_logged_in_user_guid() == $user->guid) {
	$rel = 'rel="me"';
} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $user->guid)) {
	$rel = 'rel="friend"';
}

$title = "<a href=\"" . $user->getUrl() . "\" $rel>" . $user->name . "</a>";


$metadata = "<ul class=\"elgg-list-metadata\"><li>$user->location</li>";
$metadata .= elgg_view("entity/metadata", array('entity' => $user));
$metadata .= "</ul>";

if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}

if ($user->isBanned()) {
	$params = array(
		'entity' => $user,
		'title' => $title,
		'metadata' => '<ul class="elgg-list-metadata"><li>banned</li></ul>',
	);
} else {
	$params = array(
		'entity' => $user,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $user->briefdescription,
		'content' => elgg_view('user/status', array('entity' => $user)),
	);
}

$list_body = elgg_view('page/components/list/body', $params);

echo elgg_view_image_block($icon, $list_body);
