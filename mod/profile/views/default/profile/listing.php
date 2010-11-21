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
$rel_type = "";
if (get_loggedin_userid() == $vars['entity']->guid) {
	$rel_type = 'me';
} elseif (check_entity_relationship(get_loggedin_userid(), 'friend', $vars['entity']->guid)) {
	$rel_type = 'friend';
}

if ($rel_type) {
	$rel = "rel=\"$rel_type\"";
}

if (!$banned) {
	$info .= "<p class='entity_title user'><a href=\"" . $vars['entity']->getUrl() . "\" $rel>" . $vars['entity']->name . "</a></p>";
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
