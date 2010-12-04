<?php
/**
 * Elgg user display (small)
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] The user entity
 */

$icon = elgg_view(
		"groups/icon", array(
		'entity' => $vars['entity'],
		'size' => 'tiny',
));

//get the membership type
$membership = $vars['entity']->membership;
if($membership == ACCESS_PUBLIC) {
	$mem = elgg_echo("groups:open");
} else {
	$mem = elgg_echo("groups:closed");
}

$info .= "<p class='entity-subtext groups'>" . $mem . " / <b>" . get_group_members($vars['entity']->guid, 10, 0, 0, true) ."</b> " . elgg_echo("groups:member");

//for admins only - display the feature or unfeature option
if(isadminloggedin()) {
	if($vars['entity']->featured_group == "yes"){
		$url = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/featured?group_guid=" . $vars['entity']->guid . "&action_type=unfeature");
		$wording = elgg_echo("groups:makeunfeatured");
	}else{
		$url = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/groups/featured?group_guid=" . $vars['entity']->guid . "&action_type=feature");
		$wording = elgg_echo("groups:makefeatured");
	}
	// display 'make featured' option
	$info .= "<br /><a href=\"{$url}\">{$wording}</a>";
}

$info .= "</p>";
$info .= "<p class='entity-title'><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></p>";
$info .= "<p class='entity-subtext'>" . $vars['entity']->briefdescription . "</p>";

echo elgg_view_listing($icon, $info);

?>
