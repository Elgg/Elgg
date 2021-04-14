<?php

namespace Elgg\Friends\Actions;

use Elgg\Http\ResponseBuilder;

/**
 * Action controller to decline a friend request
 *
 * @since 3.2
 */
class DeclineFriendRequestController {

	/**
	 * Remove the received friend request
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
		
		if (!$relationship->delete()) {
			return elgg_error_response(elgg_echo('friends:action:friendrequest:decline:fail'));
		}
		
		// notify requesting user about decline
		$requesting_user = get_user($relationship->guid_one);
		if ($requesting_user instanceof \ElggUser) {
			$subject = elgg_echo('friends:notification:request:decline:subject', [$receiving_user->getDisplayName()], $requesting_user->getLanguage());
			$message = elgg_echo('friends:notification:request:decline:message', [
				$receiving_user->getDisplayName(),
			], $requesting_user->getLanguage());
			
			$params = [
				'action' => 'friendrequest:decline',
				'object' => $requesting_user,
				'friend' => $receiving_user,
			];
			
			notify_user($requesting_user->guid, $receiving_user->guid, $subject, $message, $params);
		}
		
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:decline:success'));
	}
}
