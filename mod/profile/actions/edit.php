<?php

	/**
	 * Elgg profile plugin edit action
	 *
	 * @package ElggProfile
	 */

	// Load configuration
		global $CONFIG;

		gatekeeper();

	// Get profile fields
		$input = array();
		$accesslevel = get_input('accesslevel');
		if (!is_array($accesslevel)) {
			$accesslevel = array();
		}

		/**
		 * wrapper for recursive array walk decoding
		 */
		function profile_array_decoder(&$v) {
			$v = html_entity_decode($v, ENT_COMPAT, 'UTF-8');
		}


		foreach($CONFIG->profile as $shortname => $valuetype) {
			// the decoding is a stop gag to prevent &amp;&amp; showing up in profile fields
			// because it is escaped on both input (get_input()) and output (view:output/text). see #561 and #1405.
			// must decode in utf8 or string corruption occurs. see #1567.
			$value = get_input($shortname);
			if (is_array($value)) {
				array_walk_recursive($value, 'profile_array_decoder');
			} else {
				$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
			}

			// limit to reasonable sizes.
			if (!is_array($value) && $valuetype != 'longtext' && elgg_strlen($value) > 250) {
				$error = sprintf(elgg_echo('profile:field_too_long'), elgg_echo("profile:{$shortname}"));
				register_error($error);
				forward($_SERVER['HTTP_REFERER']);
			}

			if ($valuetype == 'tags') {
				$value = string_to_tag_array($value);
			}

			$input[$shortname] = $value;
		}

	// Get the page owner to see if the currently logged in user canEdit() the page owner.

		$user = page_owner_entity();
		if (!$user) {
			$user = $_SESSION['user'];

			// @todo this doesn't make sense...???
			set_page_owner($user->getGUID());
		}
		if ($user->canEdit()) {

			// Save stuff
			if (sizeof($input) > 0)
				foreach($input as $shortname => $value) {
					//$user->$shortname = $value;
					remove_metadata($user->guid, $shortname);
					if (isset($accesslevel[$shortname])) {
						$access_id = (int) $accesslevel[$shortname];
					} else {
						// this should never be executed since the access level should always be set
						$access_id = ACCESS_PRIVATE;
					}
					if (is_array($value)) {
						$i = 0;
						foreach($value as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, $shortname, $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
						create_metadata($user->guid, $shortname, $value, 'text', $user->guid, $access_id);
					}
				}
			$user->save();

			// Notify of profile update
			trigger_elgg_event('profileupdate',$user->type,$user);

			//add to river if edited by self
			if (get_loggedin_userid() == $user->guid) {
				add_to_river('river/user/default/profileupdate','update',$_SESSION['user']->guid,$_SESSION['user']->guid,get_default_access($_SESSION['user']));
			}

			system_message(elgg_echo("profile:saved"));

			// Forward to the user's profile
			forward($user->getUrl());

		} else {
	// If we can't, display an error

			system_message(elgg_echo("profile:noaccess"));
		}

?>
