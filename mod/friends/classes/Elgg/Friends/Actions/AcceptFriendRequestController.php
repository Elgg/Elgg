<?php

namespace Elgg\Friends\Actions;

use Elgg\Http\ResponseBuilder;
use Elgg\Friends\Notifications;

/**
 * Action controller to accept a friend request
 *
 * @since 3.2
 */
class AcceptFriendRequestController {

	/**
	 * Accept the received friend request
	 *
	 * @param \Elgg\Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(\Elgg\Request $request) {
		
		$id = (int) $request->getParam('id');
		if (empty($id)) {
			return elgg_error_response(elgg_echo('error:missing_data'));
		}
		
		$relationship = get_relationship($id);
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friendrequest') {
			return elgg_error_response(elgg_echo('error:missing_data'));
		}
		
		$receiving_user = get_user($relationship->guid_two);
		if (!$receiving_user instanceof \ElggUser || !$receiving_user->canEdit()) {
			return elgg_error_response(elgg_echo('actionunauthorized'));
		}
		
		$requesting_user = get_user($relationship->guid_one);
		if (!$requesting_user instanceof \ElggUser) {
			return elgg_error_response(elgg_echo('error:missing_data'));
		}
		
		// add friends
		$receiving_user->addFriend($requesting_user->guid, true);
		$requesting_user->addFriend($receiving_user->guid, true);
		
		// notify requesting user about acceptance
		Notifications::sendAcceptedFriendRequestNotification($requesting_user, $receiving_user);
		
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:accept:success'));
	}
}
