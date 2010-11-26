<?php

	/**
	 * Add a user to a group
	 *
	 * @package ElggGroups
	 */

	// Load configuration
	global $CONFIG;

	gatekeeper();

	$logged_in_user = get_loggedin_user();

	$user_guid = get_input('user_guid');
	if (!is_array($user_guid))
		$user_guid = array($user_guid);
	$group_guid = get_input('group_guid');

	if (sizeof($user_guid))
	{
		foreach ($user_guid as $u_id)
		{
			$user = get_entity($u_id);
			$group = get_entity($group_guid);

			if ( $user && $group) {

				//if (get_loggedin_userid() == $group->owner_guid)
				if ($group->canEdit())
				{

					// If the group is open or the user has requested membership
					if (
						(check_entity_relationship($user->guid, 'membership_request', $group->guid)) ||
						($group->isPublicMembership())
						)
					{

						if (!$group->isMember($user))
						{
							// Remove relationships
							remove_entity_relationship($group->guid, 'invited', $user->guid);
							remove_entity_relationship($user->guid, 'membership_request', $group->guid);

							//add_entity_relationship($user->guid, 'member', $group->guid);
							$group->join($user);

							// send welcome email
							notify_user($user->getGUID(), $group->owner_guid,
								sprintf(elgg_echo('groups:welcome:subject'), $group->name),
								sprintf(elgg_echo('groups:welcome:body'), $user->name, $group->name, $group->getURL()),
								NULL);

							system_message(elgg_echo('groups:addedtogroup'));
						}
						else
							register_error(elgg_echo("groups:cantjoin"));
					}
					else
					{
						if ($user->isFriend())
						{

							// Create relationship
							add_entity_relationship($group->guid, 'invited', $user->guid);

							// Send email
							$url = "{$CONFIG->url}pg/groups/invited?user_guid={$user->guid}&group_guid={$group->guid}";
							if (notify_user($user->getGUID(), $group->owner_guid,
									sprintf(elgg_echo('groups:invite:subject'), $user->name, $group->name),
									sprintf(elgg_echo('groups:invite:body'), $user->name, $logged_in_user->name, $group->name, $url),
									NULL))
								system_message(elgg_echo("groups:userinvited"));
							else
								register_error(elgg_echo("groups:usernotinvited"));

						}
						else
							register_error(elgg_echo("groups:usernotinvited"));
					}
				}
				else
					register_error(elgg_echo("groups:notowner"));
			}
		}
	}

	forward($_SERVER['HTTP_REFERER']);

?>
