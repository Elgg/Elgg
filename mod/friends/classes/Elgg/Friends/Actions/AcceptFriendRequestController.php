<?php

namespace Elgg\Friends\Actions;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;
use Elgg\Friends\Notifications;

/**
 * Action controller to accept a friend request
 *
 * @since 3.2
 */
class AcceptFriendRequestController extends \Elgg\Controllers\GenericAction {

	protected \ElggUser $receiving_user;
	
	protected \ElggUser $requesting_user;
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws BadRequestException
	 * @throws EntityPermissionsException
	 * @throws ValidationException
	 */
	protected function validate(): void {
		$id = (int) $this->request->getParam('id');
		if (empty($id)) {
			throw new ValidationException(elgg_echo('ValidationException:field:required', ['id']));
		}
		
		$relationship = elgg_get_relationship($id);
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friendrequest') {
			throw new BadRequestException(elgg_echo('error:missing_data'));
		}
		
		$receiving_user = get_user($relationship->guid_two);
		if (!$receiving_user instanceof \ElggUser || !$receiving_user->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		$requesting_user = get_user($relationship->guid_one);
		if (!$requesting_user instanceof \ElggUser) {
			throw new BadRequestException(elgg_echo('error:missing_data'));
		}
		
		$this->receiving_user = $receiving_user;
		$this->requesting_user = $requesting_user;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(): void {
		$this->receiving_user->addFriend($this->requesting_user->guid, true);
		$this->requesting_user->addFriend($this->receiving_user->guid, true);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): OkResponse {
		// notify requesting user about acceptance
		Notifications::sendAcceptedFriendRequestNotification($this->requesting_user, $this->receiving_user);
		
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:accept:success'));
	}
}
