<?php

namespace Elgg\Users;

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Event handlers for user settings
 *
 * @since 4.0
 */
class Settings {

	/**
	 * Set a user's password
	 *
	 * Returns false indicating failure if change was needed
	 *
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setPassword(\Elgg\Event $event) {
	
		$actor = elgg_get_logged_in_user_entity();
		if (!$actor instanceof \ElggUser) {
			return;
		}
	
		$user = $event->getUserParam();
		$request = $event->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$password = (string) $request->getParam('password', null, false);
		$password2 = (string) $request->getParam('password2', null, false);
	
		if (!$password) {
			return;
		}
	
		if (!$actor->isAdmin() || $user->guid === $actor->guid) {
			// let admin user change anyone's password without knowing it except his own.
	
			$current_password = (string) $request->getParam('current_password', null, false);
	
			try {
				_elgg_services()->accounts->assertCurrentPassword($user, $current_password);
			} catch (RegistrationException $e) {
				$request->validation()->fail('password', '', elgg_echo('LoginException:ChangePasswordFailure'));
	
				return false;
			}
		}
	
		try {
			_elgg_services()->accounts->assertValidPassword([$password, $password2]);
		} catch (RegistrationException $e) {
			$request->validation()->fail('password', '', $e->getMessage());
	
			return false;
		}
	
		$user->setPassword($password);
		_elgg_services()->persistentLogin->handlePasswordChange($user, $actor);
		
		if (elgg_get_config('security_notify_user_password')) {
			// notify the user that their password has changed
			$user->notify('password_change', $user);
		}
	
		$request->validation()->pass('password', '', elgg_echo('user:password:success'));
	}
	
	/**
	 * Set a user's display name
	 *
	 * Returns false indicating failure if change was needed
	 *
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setName(\Elgg\Event $event) {
	
		$user = $event->getUserParam();
		
		/* @var $request \Elgg\Request */
		$request = $event->getParam('request');
		
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
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return false|void
	 */
	public static function setUsername(\Elgg\Event $event) {
	
		$user = $event->getUserParam();
		$request = $event->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
	
		$username = $request->getParam('username');
		if (!isset($username)) {
			return;
		}
	
		if (!elgg_is_admin_logged_in() && !elgg_get_config('can_change_username')) {
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
			_elgg_services()->accounts->assertValidUsername($username, true);
		} catch (RegistrationException $ex) {
			$request->validation()->fail('username', $username, $ex->getMessage());
	
			return false;
		}
	
		$user->username = $username;
	
		$request->validation()->pass('username', $username, elgg_echo('user:username:success'));
	
		// correctly forward after after a username change
		elgg_register_event_handler('response', 'action:usersettings/save', function (\Elgg\Event $event) use ($username) {
			$response = $event->getValue();
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
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public static function setLanguage(\Elgg\Event $event) {
	
		$user = $event->getUserParam();
		$request = $event->getParam('request');
		
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
		
		$who_can_change_language = elgg_get_config('who_can_change_language');
		if ($who_can_change_language === 'nobody') {
			return;
		} elseif ($who_can_change_language === 'admin_only' && !elgg_is_admin_logged_in()) {
			return;
		}
		
		if (!in_array($language, _elgg_services()->translator->getAllowedLanguages())) {
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
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return bool|void
	 */
	public static function setEmail(\Elgg\Event $event) {
		
		$actor = elgg_get_logged_in_user_entity();
		if (!$actor instanceof \ElggUser) {
			return;
		}
	
		$user = $event->getUserParam();
		$request = $event->getParam('request');
		
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
			$assert_unregistered = true;
			if ($actor->isAdmin() && $user->guid !== $actor->guid) {
				// admins changing another users email address are allowed to set it to a duplicate email address
				$assert_unregistered = false;
			}
			
			_elgg_services()->accounts->assertValidEmail($email, $assert_unregistered);
		} catch (RegistrationException $ex) {
			$request->validation()->fail('email', $email, $ex->getMessage());
	
			return false;
		}
	
		if (_elgg_services()->config->security_email_require_password && $user->guid === $actor->guid) {
			try {
				// validate password
				_elgg_services()->accounts->assertCurrentPassword($user, (string) $request->getParam('email_password'));
			} catch (RegistrationException $e) {
				$request->validation()->fail('email', $email, elgg_echo('email:save:fail:password'));
				return false;
			}
		}
	
		$params = $event->getParams();
		$params['email'] = $email;
	
		if (!elgg_trigger_event_results('change:email', 'user', $params, true)) {
			return;
		}
		
		if (_elgg_services()->config->security_email_require_confirmation && (!$actor->isAdmin() || $user->guid === $actor->guid)) {
			// validate the new email address
			try {
				_elgg_services()->accounts->requestNewEmailValidation($user, $email);
				
				$request->validation()->pass('email', $email, elgg_echo('account:email:request:success', [$email]));
				return true;
			} catch (InvalidArgumentException $e) {
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
	 * @param \Elgg\Event $event 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public static function setDefaultAccess(\Elgg\Event $event) {
	
		if (!_elgg_services()->config->allow_user_default_access) {
			return;
		}
	
		$user = $event->getUserParam();
		$request = $event->getParam('request');
		
		if (!$user instanceof \ElggUser || !$request instanceof Request) {
			return;
		}
		
		$default_access = $request->getParam('default_access');
		if (!isset($default_access)) {
			return;
		}
	
		if (!$user->setMetadata('elgg_default_access', $default_access)) {
			$request->validation()->fail('default_access', $default_access, elgg_echo('user:default_access:failure'));
			return;
		}
		
		$request->validation()->pass('default_access', $default_access, elgg_echo('user:default_access:success'));
	}
}
