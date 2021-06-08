<?php

namespace Elgg\Users;

use Elgg\Exceptions\LoginException;
use Elgg\Http\ResponseBuilder;

/**
 * User Validation related hooks/events
 *
 * @since 4.0
 */
class Validation {

	/**
	 * Notify the user that their account is approved
	 *
	 * @param \Elgg\Event $event 'validate:after', 'user'
	 *
	 * @return void
	 */
	public static function notifyUserAfterValidation(\Elgg\Event $event) {
		
		if (!(bool) elgg_get_config('require_admin_validation')) {
			return;
		}
		
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$site = elgg_get_site_entity();
		
		$subject = elgg_echo('account:notification:validation:subject', [$site->getDisplayName()], $user->getLanguage());
		$body = elgg_echo('account:notification:validation:body', [
			$site->getDisplayName(),
			$site->getURL(),
		], $user->getLanguage());
		
		$params = [
			'action' => 'account:validated',
			'object' => $user,
		];
		
		notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
	}
	
	/**
	 * Check if new users need to be validated by an administrator
	 *
	 * @param \Elgg\Hook $hook 'register', 'user'
	 *
	 * @return void
	 */
	public static function checkAdminValidation(\Elgg\Hook $hook) {
		
		if (!(bool) elgg_get_config('require_admin_validation')) {
			return;
		}
		
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user, $hook) {
			
			if ($user->isEnabled()) {
				// disable the user until validation
				$user->disable('admin_validation_required', false);
			}
			
			// set validation status
			$user->setValidationStatus(false);
			
			// store a flag in session so we can forward the user correctly
			$session = elgg_get_session();
			$session->set('admin_validation', true);
			
			if (elgg_get_config('admin_validation_notification') === 'direct') {
				self::notifyAdminsAboutPendingUsers($hook);
			}
		});
	}
	
	/**
	 * Send a notification to all admins that there are pending user validations
	 *
	 * @param \Elgg\Hook $hook various hooks
	 *
	 * @return void
	 */
	public static function notifyAdminsAboutPendingUsers(\Elgg\Hook $hook) {
		
		if (empty(elgg_get_config('admin_validation_notification'))) {
			return;
		}
		
		$unvalidated_count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
			return elgg_count_entities([
				'type' => 'user',
				'metadata_name_value_pairs' => [
					'validated' => 0,
				],
			]);
		});
		if (empty($unvalidated_count)) {
			// shouldn't be able to get here because this function is triggered when a user is marked as unvalidated
			return;
		}
		
		$site = elgg_get_site_entity();
		$admins = elgg_get_admins([
			'limit' => false,
			'batch' => true,
		]);
		
		$url = elgg_normalize_url('admin/users/unvalidated');
		
		/* @var $admin \ElggUser */
		foreach ($admins as $admin) {
			$user_setting = $admin->getPrivateSetting('admin_validation_notification');
			if (isset($user_setting) && !(bool) $user_setting) {
				continue;
			}
			
			$subject = elgg_echo('admin:notification:unvalidated_users:subject', [$site->getDisplayName()], $admin->getLanguage());
			$body = elgg_echo('admin:notification:unvalidated_users:body', [
				$unvalidated_count,
				$site->getDisplayName(),
				$url,
			], $admin->getLanguage());
			
			$params = [
				'action' => 'admin:unvalidated',
				'object' => $admin,
			];
			notify_user($admin->guid, $site->guid, $subject, $body, $params, ['email']);
		}
	}
	
	/**
	 * Prevent unvalidated users from logging in
	 *
	 * @param \Elgg\Event $event 'login:before', 'user'
	 *
	 * @return void
	 *
	 * @throws LoginException
	 */
	public static function preventUserLogin(\Elgg\Event $event) {
		
		if (!(bool) elgg_get_config('require_admin_validation')) {
			return;
		}
		
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
			if ($user->isEnabled() && $user->isValidated() !== false) {
				return;
			}
			
			throw new LoginException(elgg_echo('LoginException:AdminValidationPending'));
		});
	}
	
	/**
	 * Set the correct forward url after user registration
	 *
	 * @param \Elgg\Hook $hook 'response', 'action:register'
	 *
	 * @return void|ResponseBuilder
	 */
	public static function setRegistrationForwardUrl(\Elgg\Hook $hook) {
		
		$response = $hook->getValue();
		if (!$response instanceof ResponseBuilder) {
			return;
		}
		
		$session = elgg_get_session();
		if (!$session->get('admin_validation')) {
			return;
		}
		
		// if other plugins already have set forwarding, don't do anything
		if (!empty($response->getForwardURL()) && $response->getForwardURL() !== REFERER) {
			return;
		}
		
		$response->setForwardURL(elgg_generate_url('account:validation:pending'));
		
		return $response;
	}
}
