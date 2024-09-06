<?php

namespace Elgg\Users;

use Elgg\Exceptions\LoginException;
use Elgg\Http\ResponseBuilder;

/**
 * User Validation related events
 *
 * @since 4.0
 */
class Validation {

	/**
	 * Adds river activity that a new user joined the site
	 *
	 * @param \Elgg\Event $event 'validate:after', 'user'
	 *
	 * @return void
	 */
	public static function addRiverActivityAfterValidation(\Elgg\Event $event) {
		if (!(bool) elgg_get_config('user_joined_river')) {
			return;
		}
		
		elgg_create_river_item([
			'action_type' => 'join',
			'subject_guid' => $event->getObject()->guid,
			'object_guid' => elgg_get_site_entity()->guid,
		]);
	}
	
	/**
	 * Check if new users need to be validated by an administrator
	 *
	 * @param \Elgg\Event $event 'register', 'user'
	 *
	 * @return void
	 */
	public static function checkAdminValidation(\Elgg\Event $event) {
		
		if (!(bool) elgg_get_config('require_admin_validation')) {
			return;
		}
		
		$user = $event->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
			
			if ($user->isEnabled()) {
				// disable the user until validation
				$user->disable('admin_validation_required', false);
			}
			
			// set validation status
			$user->setValidationStatus(false);
			
			// store a flag in session so we can forward the user correctly
			$session = elgg_get_session();
			$session->set('admin_validation', true);
			
			self::notifyAdminsAboutPendingUsers();
		});
	}
	
	/**
	 * Send a notification to all admins that there are pending user validations
	 *
	 * @return void
	 */
	protected static function notifyAdminsAboutPendingUsers(): void {
		if (!(bool) elgg_get_config('admin_validation_notification')) {
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
			$notification_preferences = $admin->getNotificationSettings('admin_validation_notification');
			$notification_preferences = array_keys(array_filter($notification_preferences));
			if (empty($notification_preferences)) {
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
			
			notify_user($admin->guid, $site->guid, $subject, $body, $params, $notification_preferences);
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
	 * @param \Elgg\Event $event 'response', 'action:register'
	 *
	 * @return void|ResponseBuilder
	 */
	public static function setRegistrationForwardUrl(\Elgg\Event $event) {
		
		$response = $event->getValue();
		if (!$response instanceof ResponseBuilder) {
			return;
		}
		
		$session = elgg_get_session();
		if (!$session->get('admin_validation')) {
			return;
		}
		
		// if other plugins already have set forwarding, don't do anything
		if (!empty($response->getForwardURL()) && $response->getForwardURL() !== REFERRER) {
			return;
		}
		
		$response->setForwardURL(elgg_generate_url('account:validation:pending'));
		
		return $response;
	}
	
	/**
	 * Remove unvalidated users after x days
	 *
	 * @param \Elgg\Event $event 'cron', 'daily'
	 *
	 * @return void
	 * @since 4.2
	 */
	public static function removeUnvalidatedUsers(\Elgg\Event $event): void {
		
		$days = (int) elgg_get_config('remove_unvalidated_users_days');
		if ($days < 1) {
			return;
		}
		
		// removing users could take a while
		set_time_limit(0);
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($days) {
			/* @var $users \ElggBatch */
			$users = elgg_get_entities([
				'type' => 'user',
				'metadata_name_value_pairs' => [
					'validated' => false,
				],
				'created_before' => "-{$days} days",
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			
			/* @var $user \ElggUser */
			foreach ($users as $user) {
				if (!$user->delete()) {
					// make sure the batch skips over the failed user in the next iteration
					$users->reportFailure();
				}
			}
		});
	}
}
