<?php

namespace Elgg\Users;

/**
 * Sends notification to a banned user
 *
 * @since 4.0
 */
class BanUserNotificationHandler {
	
	/**
	 * Send a notification to the user that the account was banned
	 *
	 * Note: this can't be handled by the delayed notification system as it won't send notifications to banned users
	 *
	 * @param \Elgg\Event $event 'ban', 'user'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		if (!_elgg_services()->config->security_notify_user_ban) {
			return;
		}
	
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$site = elgg_get_site_entity();
		$language = $user->getLanguage();
	
		$subject = elgg_echo('user:notification:ban:subject', [$site->getDisplayName()], $language);
		$body = elgg_echo('user:notification:ban:body', [
			$site->getDisplayName(),
			$site->getURL(),
		], $language);
	
		$params = [
			'action' => 'ban',
			'object' => $user,
		];
	
		notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
	}
}
