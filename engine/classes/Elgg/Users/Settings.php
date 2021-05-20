<?php

namespace Elgg\Users;

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Plugin hook handlers for user settings
 *
 * @since 4.0
 */
class Settings {

	/**
	 * Set a user's password
	 *
	 * Returns false indicating failure if change was needed
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setPassword(\Elgg\Hook $hook) {
	
		$actor = elgg_get_logged_in_user_entity();
		if (!$actor instanceof \ElggUser) {
			return;
		}
	
		$user = $hook->getUserParam();
		$request = $hook->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$password = $request->getParam('password', null, false);
		$password2 = $request->getParam('password2', null, false);
	
		if (!$password) {
			return;
		}
	
		if (!$actor->isAdmin() || $user->guid === $actor->guid) {
			// let admin user change anyone's password without knowing it except his own.
	
			$current_password = $request->getParam('current_password', null, false);
	
			try {
				elgg()->accounts->assertCurrentPassword($user, $current_password);
			} catch (RegistrationException $e) {
				$request->validation()->fail('password', '', elgg_echo('LoginException:ChangePasswordFailure'));
	
				return false;
			}
		}
	
		try {
			elgg()->accounts->assertValidPassword([$password, $password2]);
		} catch (RegistrationException $e) {
			$request->validation()->fail('password', '', $e->getMessage());
	
			return false;
		}
	
		$user->setPassword($password);
		_elgg_services()->persistentLogin->handlePasswordChange($user, $actor);
		
		if (elgg_get_config('security_notify_user_password')) {
			// notify the user that their password has changed
			$site = elgg_get_site_entity();
			
			$subject = elgg_echo('user:notification:password_change:subject', [], $user->language);
			$body = elgg_echo('user:notification:password_change:body', [
				$user->getDisplayName(),
				$site->getDisplayName(),
				elgg_generate_url('account:password:reset'),
				$site->getURL(),
			], $user->language);
			
			$params = [
				'object' => $user,
				'action' => 'password_change',
			];
			
			notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
		}
	
		$request->validation()->pass('password', '', elgg_echo('user:password:success'));
	}
	
	/**
	 * Set a user's display name
	 *
	 * Returns false indicating failure if change was needed
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setName(\Elgg\Hook $hook) {
	
		$user = $hook->getUserParam();
		
		/* @var $request \Elgg\Request */
		$request = $hook->getParam('request');
		
		$name = $request->getParam('name');
		if (!isset($name)) {
			return;
		}
	
		$name = strip_tags($name);
		if (empty($name)) {
			$request->validation()->fail('name', $request->getParam('name'), elgg_echo('user:name:fail'));
	
			return false;
		}
	
		if ($name === $user->name) {
			return;
		}
	
		$request->validation()->pass('name', $name, elgg_echo('user:name:success'));
	
		$user->name = $name;
	}
	
	/**
	 * Set a user's username
	 *
	 * Returns false indicating failure if change was needed
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setUsername(\Elgg\Hook $hook) {
	
		$user = $hook->getUserParam();
		$request = $hook->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$username = $request->getParam('username');
		if (!isset($username)) {
			return;
		}
	
		if (!elgg_is_admin_logged_in() && !elgg_get_config('can_change_username', false)) {
			return;
		}
		
		if (!$user->canEdit()) {
			return;
		}
	
		if ($user->username === $username) {
			return;
		}
	
		// check if username is valid and does not exist
		try {
			elgg()->accounts->assertValidUsername($username, true);
		} catch (RegistrationException $ex) {
			$request->validation()->fail('username', $username, $ex->getMessage());
	
			return false;
		}
	
		$user->username = $username;
	
		$request->validation()->pass('username', $username, elgg_echo('user:username:success'));
	
		// correctly forward after after a username change
		elgg_register_plugin_hook_handler('response', 'action:usersettings/save', function (\Elgg\Hook $hook) use ($username) {
			$response = $hook->getValue();
			if (!$response instanceof ResponseBuilder) {
				return;
			}
	
			if ($response->getForwardURL() === REFERRER) {
				$response->setForwardURL(elgg_generate_url('settings:account', [
					'username' => $username,
				]));
			}
	
			return $response;
		});
	}
	
	/**
	 * Set a user's language
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public static function setLanguage(\Elgg\Hook $hook) {
	
		$user = $hook->getUserParam();
		$request = $hook->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$language = $request->getParam('language');
		if (!isset($language)) {
			return;
		}
	
		if ($language === $user->language) {
			return;
		}
		
		if (!in_array($language, elgg()->translator->getAllowedLanguages())) {
			return;
		}
	
		$user->language = $language;
	
		$request->validation()->pass('language', $language, elgg_echo('user:language:success'));
	}
	
	/**
	 * Set a user's email address
	 *
	 * Returns true or false indicating success or failure if change was needed
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return bool|void
	 */
	public static function setEmail(\Elgg\Hook $hook) {
		
		$actor = elgg_get_logged_in_user_entity();
		if (!$actor instanceof \ElggUser) {
			return;
		}
	
		$user = $hook->getUserParam();
		$request = $hook->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$email = $request->getParam('email');
		if (!isset($email)) {
			return;
		}
	
		if (strcmp($email, $user->email) === 0) {
			// no change
			return;
		}
	
		try {
			elgg()->accounts->assertValidEmail($email, true);
		} catch (RegistrationException $ex) {
			$request->validation()->fail('email', $email, $ex->getMessage());
	
			return false;
		}
	
		if (elgg()->config->security_email_require_password && $user->guid === $actor->guid) {
			try {
				// validate password
				elgg()->accounts->assertCurrentPassword($user, $request->getParam('email_password'));
			} catch (RegistrationException $e) {
				$request->validation()->fail('email', $email, elgg_echo('email:save:fail:password'));
				return false;
			}
		}
	
		$hook_params = $hook->getParams();
		$hook_params['email'] = $email;
	
		if (!elgg_trigger_plugin_hook('change:email', 'user', $hook_params, true)) {
			return;
		}
		
		if (elgg()->config->security_email_require_confirmation) {
			// validate the new email address
			try {
				elgg()->accounts->requestNewEmailValidation($user, $email);
				
				$request->validation()->pass('email', $email, elgg_echo('account:email:request:success', [$email]));
				return true;
			} catch (InvalidParameterException $e) {
				$request->validation()->fail('email', $email, elgg_echo('email:save:fail:password'));
				return false;
			}
		}
		
		$user->email = $email;
		$request->validation()->pass('email', $email, elgg_echo('email:save:success'));
	}
	
	/**
	 * Set a user's default access level
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public static function setDefaultAccess(\Elgg\Hook $hook) {
	
		if (!elgg()->config->allow_user_default_access) {
			return;
		}
	
		$user = $hook->getUserParam();
		$request = $hook->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
		
		$default_access = $request->getParam('default_access');
		if (!isset($default_access)) {
			return;
		}
	
		if (!$user->setPrivateSetting('elgg_default_access', $default_access)) {
			$request->validation()->fail('default_access', $default_access, elgg_echo(elgg_echo('user:default_access:failure')));
			return;
		}
		
		$request->validation()->pass('default_access', $default_access, elgg_echo('user:default_access:success'));
	}
	
	/**
	 * Save a setting related to admin approval of new users
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public static function setAdminValidationNotification(\Elgg\Hook $hook) {
		
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser || !$user->isAdmin()) {
			return;
		}
		
		$request = $hook->getParam('request');
		if (!$request instanceof \Elgg\Request) {
			return;
		}
		
		$value = (bool) $request->getParam('admin_validation_notification', true);
		$user->setPrivateSetting('admin_validation_notification', $value);
	}
}
