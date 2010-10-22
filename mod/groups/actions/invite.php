<?php

/**
 * Invite a user to join a group
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

			if (($group instanceof ElggGroup) && ($group->canEdit()))
			{
				if (!check_entity_relationship($group->guid, 'invited', $user->guid))
				{
					if ($user->isFriend())
					{

						// Create relationship
						add_entity_relationship($group->guid, 'invited', $user->guid);

						// Send email
						$url = "{$CONFIG->url}pg/groups/invitations/{$user->username}";
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
				else
					register_error(elgg_echo("groups:useralreadyinvited"));
			}
			else
				register_error(elgg_echo("groups:notowner"));
		}
	}
}

forward($_SERVER['HTTP_REFERER']);

?>
