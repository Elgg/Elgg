<?php

namespace Elgg\Friends\Actions;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Action controller to revoke a friend request
 *
 * @since 3.2
 */
class RevokeFriendRequestController extends \Elgg\Controllers\GenericAction {
	
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
		
		$sending_user = get_user($relationship->guid_one);
		if (!$sending_user instanceof \ElggUser || !$sending_user->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		if (!$relationship->delete()) {
			throw new InternalServerErrorException(elgg_echo('friends:action:friendrequest:revoke:fail'));
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): OkResponse {
		return elgg_ok_response('', elgg_echo('friends:action:friendrequest:revoke:success'));
	}
}
