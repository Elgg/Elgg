<?php

	/**
	 * Elgg user display (small)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

		$icon = elgg_view(
				"profile/icon", array(
										'entity' => $vars['entity'],
										'size' => 'small',
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
			$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></b></p>";
			//create a view that a status plugin could extend - in the default case, this is the wire
	 		$info .= elgg_view("profile/status", array("entity" => $vars['entity']));

			$location = $vars['entity']->location;
			if (!empty($location)) {
				$info .= "<p class=\"owner_timestamp\">" . elgg_echo("profile:location") . ": " . elgg_view("output/tags",array('value' => $vars['entity']->location)) . "</p>";
			}
		}
		else
		{
			$info .= "<p><b><strike>";
			if (isadminloggedin())
				$info .= "<a href=\"" . $vars['entity']->getUrl() . "\">";
			$info .= $vars['entity']->name;
			if (isadminloggedin())
				$info .= "</a>";
			$info .= "</strike></b></p>";
		
			//$info .= "<p class=\"owner_timestamp\">" . elgg_echo('profile:banned') . "</p>";
			
		}
		
		echo elgg_view_listing($icon, $info);
			
?>