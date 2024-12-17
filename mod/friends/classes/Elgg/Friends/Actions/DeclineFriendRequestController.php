<?php

namespace Elgg\Friends\Actions;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Action controller to decline a friend request
 *
 * @since 3.2
 */
class DeclineFriendRequestController extends \Elgg\Controllers\GenericAction {
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws ValidationException
	 */
	protected function validate(): void {
		$id = (int) $this->request->getParam('id');
		if (empty($id)) {
			throw new ValidationException(elgg_echo('ValidationException:field:required', ['id']));
		}
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws BadRequestException
	 * @throws EntityPermissionsException
	 * @throws InternalServerErrorException
	 */
	protected function execute(): void {
		$relationship = elgg_get_relationship((int) $this->request->getParam('id'));
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friendrequest') {
			throw new BadRequestException(elgg_echo('error:missing_data'));
		}
		
		$receiving_user = get_user($relationship->guid_two);
		if (!$receiving_user instanceof \ElggUser || !$receiving_user->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		if (!$relationship->delete()) {
			throw new InternalServerErrorException(elgg_echo('friends:action:friendrequest:decline:fail'));
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
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): OkResponse {
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:decline:success'));
	}
}
