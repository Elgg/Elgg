<?php

namespace Elgg\Friends\Actions;

use Elgg\Http\ResponseBuilder;
use Elgg\Friends\Notifications;
use Elgg\Http\ErrorResponse;

/**
 * Controller for the add friend action
 *
 * @since 3.2
 */
class AddFriendController {

	/**
	 * The action request handler
	 *
	 * @param \Elgg\Request $request the action request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(\Elgg\Request $request) {
		
		$friend_guid = (int) $request->getParam('friend');
		$friend = get_user($friend_guid);
		$user = elgg_get_logged_in_user_entity();
		if (!$friend instanceof \ElggUser || !$user instanceof \ElggUser) {
			return elgg_error_response(elgg_echo('error:missing_data'));
		}
		
		if ($user->isFriendsWith($friend->guid)) {
			return elgg_ok_response('', elgg_echo('friends:add:duplicate', [$friend->getDisplayName()]));
		}
		
		if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			return $this->requestFriend($user, $friend);
		}
		
		return $this->addFriend($user, $friend);
	}
	
	/**
	 * Default friend behaviour, add friend
	 *
	 * @param \ElggUser $user   the logged in user
	 * @param \ElggUser $friend the new friend
	 *
	 * @return ResponseBuilder
	 */
	protected function addFriend(\ElggUser $user, \ElggUser $friend) {
		if (!$user->addFriend($friend->guid, true)) {
			return elgg_error_response(elgg_echo('friends:add:failure', [$friend->getDisplayName()]));
		}
		
		return elgg_ok_response('', elgg_echo('friends:add:successful', [$friend->getDisplayName()]));
	}
	
	/**
	 * Handle a friendship request
	 *
	 * @param \ElggUser $user   the logged in user
	 * @param \ElggUser $friend the new (potential) friend
	 *
	 * @return ResponseBuilder
	 */
	protected function requestFriend(\ElggUser $user, \ElggUser $friend) {
		
		if ($friend->isFriendsWith($user->guid)) {
			// the friend is already friends with the user, so accept the other way around automatically
			$result = $this->addFriend($user, $friend);
			
			if (!$result instanceof ErrorResponse) {
				Notifications::sendAddFriendNotification($friend, $user);
			}
			
			return $result;
		}
		
		if (check_entity_relationship($friend->guid, 'friendrequest', $user->guid)) {
			// friend requested to be friends with user, so accept request
			$friend->addFriend($user->guid, true);
			$result = $this->addFriend($user, $friend);
			
			if (!$result instanceof ErrorResponse) {
				Notifications::sendAcceptedFriendRequestNotification($friend, $user);
			}
			
			return $result;
		}
		
		if (add_entity_relationship($user->guid, 'friendrequest', $friend->guid)) {
			// friend request made, notify potential friend
			$site = elgg_get_site_entity();
			
			$subject = elgg_echo('friends:notification:request:subject', [$user->getDisplayName()], $friend->getLanguage());
			$message = elgg_echo('friends:notification:request:message', [
				$user->getDisplayName(),
				$site->getDisplayName(),
				elgg_generate_url('collection:relationship:friendrequest:pending', [
					'username' => $friend->username,
				]),
			], $friend->getLanguage());
			
			$params = [
				'action' => 'friendrequest',
				'object' => $user,
				'friend' => $friend,
			];
			notify_user($friend->guid, $user->guid, $subject, $message, $params);
			
			return elgg_ok_response('', elgg_echo('friends:request:successful', [$friend->getDisplayName()]));
		}
		
		return elgg_error_response(elgg_echo('friends:request:error', [$friend->getDisplayName()]));
	}
}
