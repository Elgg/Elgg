<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 */

$user = $vars['entity'];
$size = elgg_extract('size', $vars, 'tiny');

$icon = elgg_view_entity_icon($user, $size);

// Simple XFN
$rel = '';
if (elgg_get_logged_in_user_guid() == $user->guid) {
	$rel = 'rel="me"';
} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $user->guid)) {
	$rel = 'rel="friend"';
}

$title = "<a href=\"" . $user->getUrl() . "\" $rel>" . $user->name . "</a>";


$metadata = "<ul class=\"elgg-menu elgg-menu-metadata\"><li>$user->location</li>";
$metadata .= elgg_view("entity/metadata", array('entity' => $user));
$metadata .= "</ul>";

if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}

if (elgg_get_context() == 'gallery') {
	echo $icon;
} else {
	if ($user->isBanned()) {
		$banned = elgg_echo('banned');
		$params = array(
			'entity' => $user,
			'title' => $title,
			'metadata' => '<ul class="elgg-menu elgg-menu-metadata"><li>$banned</li></ul>',
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

	$list_body = elgg_view('page/components/summary', $params);

	echo elgg_view_image_block($icon, $list_body);
}
