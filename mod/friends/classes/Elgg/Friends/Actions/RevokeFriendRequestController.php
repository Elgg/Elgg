<?php

namespace Elgg\Friends\Actions;

use Elgg\Http\ResponseBuilder;

/**
 * Action controller to revoke a friend request
 *
 * @since 3.2
 */
class RevokeFriendRequestController {

	/**
	 * Remove the sent friend request
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
		
		$sending_user = get_user($relationship->guid_one);
		if (!$sending_user instanceof \ElggUser || !$sending_user->canEdit()) {
			return elgg_error_response(elgg_echo('actionunauthorized'));
		}
		
		if (!$relationship->delete()) {
			return elgg_error_response(elgg_echo('friends:action:friendrequest:revoke:fail'));
		}
		
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:revoke:success'));
	}
}
