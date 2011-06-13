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
										'size' => 'small',
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
			$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\" $rel>" . $vars['entity']->name . "</a></b></p>";
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

			$info .= '<div id="profile_banned">';
			$info .= elgg_echo('profile:banned');
			$info .= '</div>';
		}

		echo elgg_view_listing($icon, $info);

?>