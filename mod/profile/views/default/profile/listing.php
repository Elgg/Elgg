<?php
/**
 * Elgg user display (small)
 *
 * @package ElggProfile
 *
 * @uses $vars['entity'] The user entity
 */

$icon = elgg_view(
		"profile/icon", array(
								'entity' => $vars['entity'],
								'size' => 'tiny',
							  )
);
			
$banned = $vars['entity']->isBanned();
	
// Simple XFN
$rel = "";
if (page_owner() == $vars['entity']->guid)
	$rel = 'me';
else if (check_entity_relationship(page_owner(), 'friend', $vars['entity']->guid))
	$rel = 'friend';
		
if (!$banned) {
	$info .= "<p class='entity_title user'><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></p>";
	$location = $vars['entity']->location;
	if (!empty($location)) {
		$info .= "<p class='entity_subtext user'>" . elgg_echo("profile:location") . ": " . elgg_view("output/tags",array('value' => $vars['entity']->location)) . "</p>";
	}
	//create a view that a status plugin could extend - in the default case, this is the wire
	$info .= elgg_view("profile/status", array("entity" => $vars['entity']));
}else{
	$info .= "<p class='entity_title user banned'>";
	if (isadminloggedin())
		$info .= "<a href=\"" . $vars['entity']->getUrl() . "\">";
	$info .= $vars['entity']->name;
	if (isadminloggedin())
		$info .= "</a>";
	$info .= "</p>";
}
		
echo elgg_view_listing($icon, $info);