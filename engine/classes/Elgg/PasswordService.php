<?php

namespace Elgg;

use Elgg\Exceptions\RuntimeException;

/**
 * Password service
 *
 * @internal
 * @since 1.10.0
 */
final class PasswordService {

	/**
	 * Constructor
	 *
	 * @throws RuntimeException
	 */
	public function __construct() {
		if (!function_exists('password_hash')) {
			throw new RuntimeException('password_hash and associated functions are required.');
		}
	}

	/**
	 * Determine if the password hash needs to be rehashed
	 *
	 * If the answer is true, after validating the password using password_verify, rehash it.
	 *
	 * @param string $hash The hash to test
	 *
	 * @return bool
	 */
	public function needsRehash(string $hash): bool {
		return password_needs_rehash($hash, PASSWORD_DEFAULT);
	}

	/**
	 * Verify a password against a hash using a timing attack resistant approach
	 *
	 * @param string $password The password to verify
	 * @param string $hash     The hash to verify against
	 *
	 * @return bool
	 */
	public function verify(string $password, string $hash): bool {
		return password_verify($password, $hash);
	}

	/**
	 * Hash a password for storage using password_hash()
	 *
	 * @param string $password Password in clear text
	 *
	 * @return string|false
	 */
	public function generateHash(string $password) {
		return password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * Generate and send a password request email to a given user's registered email address.
	 *
	 * @param \ElggUser $user the user to notify
	 *
	 * @return void
	 */
	public function requestNewPassword(\ElggUser $user): void {
		// generate code
		$code = elgg_generate_password();
		$user->passwd_conf_code = $code;
		$user->passwd_conf_time = time();

		// generate link
		$link = elgg_generate_url('account:password:change', [
			'u' => $user->guid,
			'c' => $code,
		]);
		$link = _elgg_services()->urlSigner->sign($link, '+1 day');

		// generate email
		$ip_address = _elgg_services()->request->getClientIp();
		$message = _elgg_services()->translator->translate('email:changereq:body', [
			$ip_address,
			$link,
		], $user->getLanguage());
		
		$subject = _elgg_services()->translator->translate('email:changereq:subject', [], $user->getLanguage());

		$params = [
			'action' => 'requestnewpassword',
			'object' => $user,
			'ip_address' => $ip_address,
			'link' => $link,
			'apply_muting' => false,
			'add_mute_link' => false,
		];
		
		notify_user($user->guid, elgg_get_site_entity()->guid, $subject, $message, $params, 'email');
	}

	/**
	 * Validate and change password for a user.
	 *
	 * @param \ElggUser $user      The user
	 * @param string    $conf_code Confirmation code as sent in the request email.
	 * @param string    $password  Optional new password, if not randomly generated.
	 *
	 * @return bool
	 *
	 * @since 4.3
	 */
	public function saveNewPassword(\ElggUser $user, string $conf_code, string $password = null): bool {
		if ($password === null) {
			$password = elgg_generate_password();
			$reset = true;
		} else {
			$reset = false;
		}

		$saved_code = $user->passwd_conf_code;
		$code_time = (int) $user->passwd_conf_time;
		$codes_match = _elgg_services()->crypto->areEqual($saved_code, $conf_code);

		if (!$saved_code || !$codes_match) {
			return false;
		}

		// Discard for security if it is 24h old
		if (!$code_time || $code_time < time() - 24 * 60 * 60) {
			return false;
		}

		$user->setPassword($password);
		
		unset($user->passwd_conf_code);
		unset($user->passwd_conf_time);
		
		// reset the logins failures
		elgg_reset_authentication_failures($user);

		$action = $reset ? 'resetpassword' : 'changepassword';

		$message = _elgg_services()->translator->translate("email:{$action}:body", [$user->username, $password], $user->getLanguage());
		$subject = _elgg_services()->translator->translate("email:{$action}:subject", [], $user->getLanguage());

		$params = [
			'action' => $action,
			'object' => $user,
			'password' => $password,
			'apply_muting' => false,
		];

		notify_user($user->guid, elgg_get_site_entity()->guid, $subject, $message, $params, 'email');

		return true;
	}
}
