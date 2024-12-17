<?php

namespace Elgg\Friends\Actions;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Friends\Notifications;
use Elgg\Http\OkResponse;

/**
 * Controller for the add friend action
 *
 * @since 3.2
 */
class AddFriendController extends \Elgg\Controllers\GenericAction {
	
	protected \ElggUser $user;
	
	protected \ElggUser $friend;
	
	protected bool $request_sent = false;
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws BadRequestException
	 * @throws ValidationException
	 */
	protected function validate(): void {
		$friend_guid = (int) $this->request->getParam('friend');
		if (empty($friend_guid)) {
			throw new ValidationException(elgg_echo('ValidationException:field:required', ['friend']));
		}
		
		$friend = get_user($friend_guid);
		$user = elgg_get_logged_in_user_entity();
		if (!$friend instanceof \ElggUser || !$user instanceof \ElggUser) {
			throw new BadRequestException(elgg_echo('error:missing_data'));
		}
		
		if ($user->isFriendsWith($friend->guid)) {
			throw new BadRequestException(elgg_echo('friends:add:duplicate', [$friend->getDisplayName()]));
		}
		
		$this->user = $user;
		$this->friend = $friend;
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws InternalServerErrorException
	 */
	protected function execute(): void {
		
		$request_needed = (bool) elgg_get_plugin_setting('friend_request', 'friends');
		if (!$request_needed) {
			// directly add a friend
			if (!$this->user->addFriend($this->friend->guid, true)) {
				throw new InternalServerErrorException(elgg_echo('friends:add:failure', [$this->friend->getDisplayName()]));
			}
			
			return;
		}
		
		if ($this->friend->isFriendsWith($this->user->guid)) {
			// the friend is already friends with the user, so accept the other way around automatically
			if (!$this->user->addFriend($this->friend->guid, true)) {
				throw new InternalServerErrorException(elgg_echo('friends:add:failure', [$this->friend->getDisplayName()]));
			}
			
			Notifications::sendAddFriendNotification($this->friend, $this->user);
			
			return;
		}
		
		if ($this->friend->hasRelationship($this->user->guid, 'friendrequest')) {
			if (!$this->user->addFriend($this->friend->guid, true)) {
				throw new InternalServerErrorException(elgg_echo('friends:add:failure', [$this->friend->getDisplayName()]));
			}
			
			Notifications::sendAcceptedFriendRequestNotification($this->friend, $this->user);
			
			return;
		}
		
		// request needed
		if (!$this->user->addRelationship($this->friend->guid, 'friendrequest')) {
			throw new InternalServerErrorException(elgg_echo('friends:request:error', [$this->friend->getDisplayName()]));
		}
		
		$this->request_sent = true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): OkResponse {
		if (!$this->request_sent) {
			return elgg_ok_response('', elgg_echo('friends:add:successful', [$this->friend->getDisplayName()]));
		}
		
		// friend request made, notify potential friend
		$subject = elgg_echo('friends:notification:request:subject', [$this->user->getDisplayName()], $this->friend->getLanguage());
		$message = elgg_echo('friends:notification:request:message', [
			$this->user->getDisplayName(),
			elgg_get_site_entity()->getDisplayName(),
			elgg_generate_url('collection:relationship:friendrequest:pending', [
				'username' => $this->friend->username,
			]),
		], $this->friend->getLanguage());
		
		$params = [
			'action' => 'friendrequest',
			'object' => $this->user,
			'friend' => $this->friend,
		];
		notify_user($this->friend->guid, $this->user->guid, $subject, $message, $params);
		
		return elgg_ok_response('', elgg_echo('friends:request:successful', [$this->friend->getDisplayName()]));
	}
}
