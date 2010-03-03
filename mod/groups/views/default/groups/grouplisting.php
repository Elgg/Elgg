<?php
	/**
	 * Elgg user display (small)
	 *
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 *
	 * @uses $vars['entity'] The user entity
	 */

	$icon = elgg_view(
			"groups/icon", array(
									'entity' => $vars['entity'],
									'size' => 'small',
								)
		);

	//get the membership type
	$membership = $vars['entity']->membership;
	if($membership == 2)
		$mem = elgg_echo("groups:open");
	else
		$mem = elgg_echo("groups:closed");

	//for admins display the feature or unfeature option
	if($vars['entity']->featured_group == "yes"){
		$url = elgg_add_action_tokens_to_url($vars['url'] . "action/groups/featured?group_guid=" . $vars['entity']->guid . "&action=unfeature");
		$wording = elgg_echo("groups:makeunfeatured");
	}else{
		$url = elgg_add_action_tokens_to_url($vars['url'] . "action/groups/featured?group_guid=" . $vars['entity']->guid . "&action=feature");
		$wording = elgg_echo("groups:makefeatured");
	}

	$info .= "<div class=\"groupdetails\"><p>" . $mem . " / <b>" . get_group_members($vars['entity']->guid, 10, 0, 0, true) ."</b> " . elgg_echo("groups:member") . "</p>";
	//if admin, show make featured option
	if(isadminloggedin())
		$info .= "<p><a href=\"{$url}\">{$wording}</a></p>";
	$info .= "</div>";
	$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></b></p>";
	$info .= "<p class=\"owner_timestamp\">" . $vars['entity']->briefdescription . "</p>";

	// num users, last activity, owner etc

	echo elgg_view_listing($icon, $info);

?>
