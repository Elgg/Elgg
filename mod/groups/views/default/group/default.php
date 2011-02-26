<?php 
/**
 * Group entity view
 * 
 * @package ElggGroups
 */

$group = $vars['entity'];

$icon = elgg_view_entity_icon($group, 'tiny');

//get the membership type
$membership = $group->membership;
if ($membership == ACCESS_PUBLIC) {
	$mem = elgg_echo("groups:open");
} else {
	$mem = elgg_echo("groups:closed");
}

// number of members
$num_members = get_group_members($group->guid, 10, 0, 0, true);
$members_string = elgg_echo('groups:member');

$metadata = "<ul class=\"elgg-menu elgg-menu-metadata\"><li>$mem</li>";
$metadata .= "<li>$num_members $members_string</li>";

// feature link
if (elgg_is_admin_logged_in()) {
	if ($group->featured_group == "yes") {
		$url = "action/groups/featured?group_guid={$group->guid}&action_type=unfeature";
		$wording = elgg_echo("groups:makeunfeatured");
	} else {
		$url = "action/groups/featured?group_guid={$group->guid}&action_type=feature";
		$wording = elgg_echo("groups:makefeatured");
	}
	$feature_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => $wording,
		'is_action' => true,
	));
	$metadata .= "<li>$feature_link</li>";
}

$metadata .= elgg_view("entity/metadata", array('entity' => $group));

$metadata .= "</ul>";

if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}


if ($vars['full']) {
	echo elgg_view("groups/profile/profile_block", $vars);
} else {
	// brief view

	$params = array(
		'entity' => $group,
		'metadata' => $metadata,
		'subtitle' => $group->briefdescription,
	);
	$list_body = elgg_view('page/components/list/body', $params);

	echo elgg_view_image_block($icon, $list_body);
}
