<?php

namespace Elgg\Actions;

use Elgg\Action;
use Elgg\Http\ResponseFactory;
use LoginException;

/**
 * Elgg login action
 */
class LoginAction {

	/**
	 * Execute action
	 *
	 * @param Action $action Action
	 *
	 * @return ResponseFactory
	 */
	public function __invoke(Action $action) {

		$session = elgg_get_session();

		// set forward url
		if ($session->has('last_forward_from')) {
			$forward_url = $session->get('last_forward_from');
			$forward_source = 'last_forward_from';
		} else if ($action->getParam('returntoreferer')) {
			$forward_url = REFERER;
			$forward_source = 'return_to_referer';
		} else {
			// forward to main index page
			$forward_url = '';
			$forward_source = null;
		}

		$username = $action->getParam('username');
		$password = $action->getParam('password', null, false);
		$persistent = (bool) $action->getParam("persistent");
		$result = false;

		if (empty($username) || empty($password)) {
			return elgg_error_response(elgg_echo('login:empty'));
		}

		// check if logging in with email address
		if (strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
			$username = $users[0]->username;
		}

		$user = get_user_by_username($username);

		$result = elgg_authenticate($username, $password);
		if ($result !== true) {
			// was due to missing hash?
			if ($user && !$user->password_hash) {
				// if we did this in pam_auth_userpass(), visitors could sniff account usernames from
				// email addresses. Instead, this lets us give the visitor only the information
				// they provided.
				elgg_get_session()->set('forgotpassword:hash_missing', $action->getParam('username'));
				$output = [
					'forward' => 'forgotpassword',
				];

				return elgg_ok_response($output, '', 'forgotpassword');
			}

			return elgg_error_response($result);
		}

		if (!$user) {
			return elgg_error_response(elgg_echo('login:baduser'));
		}

		try {
			login($user, $persistent);
			// re-register at least the core language file for users with language other than site default
			register_translations(dirname(dirname(__FILE__)) . "/languages/");
		} catch (LoginException $e) {
			return elgg_error_response($e->getMessage());
		}

		$message = elgg_echo('loginok', [], $user->getLanguage(get_current_language()));

		// clear after login in case login fails
		$session->remove('last_forward_from');

		$params = ['user' => $user, 'source' => $forward_source];
		$forward_url = elgg_trigger_plugin_hook('login:forward', 'user', $params, $forward_url);

		$output = [
			'forward' => $forward_url,
		];

		if (elgg_is_xhr()) {
			// Hold the system messages until the client refreshes the page.
			set_input('elgg_fetch_messages', 0);
		}

		return elgg_ok_response($output, $message, $forward_url);

	}
}