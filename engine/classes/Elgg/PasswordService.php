<?php
namespace Elgg;

/**
 * PRIVATE CLASS. API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package Elgg.Core
 * @access  private
 * @since   1.10.0
 */
final class PasswordService {

	/**
	 * Constructor
	 */
	public function __construct() {
		if (!function_exists('password_hash')) {
			throw new \RuntimeException("password_hash and associated functions are required.");
		}
	}

	/**
	 * Determine if the password hash needs to be rehashed
	 *
	 * If the answer is true, after validating the password using password_verify, rehash it.
	 *
	 * @param string $hash The hash to test
	 *
	 * @return boolean True if the password needs to be rehashed.
	 */
	function needsRehash($hash) {
		return password_needs_rehash($hash, PASSWORD_DEFAULT);
	}

	/**
	 * Verify a password against a hash using a timing attack resistant approach
	 *
	 * @param string $password The password to verify
	 * @param string $hash     The hash to verify against
	 *
	 * @return boolean If the password matches the hash
	 */
	function verify($password, $hash) {
		return password_verify($password, $hash);
	}

	/**
	 * Hash a password for storage using password_hash()
	 *
	 * @param string $password Password in clear text
	 *
	 * @return string
	 */
	function generateHash($password) {
		return password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * Generate and send a password request email to a given user's registered email address.
	 *
	 * @param int $user_guid User GUID
	 *
	 * @return bool
	 */
	function sendNewPasswordRequest($user_guid) {
		$user_guid = (int) $user_guid;

		$user = _elgg_services()->entityTable->get($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}

		// generate code
		$code = generate_random_cleartext_password();
		$user->setPrivateSetting('passwd_conf_code', $code);
		$user->setPrivateSetting('passwd_conf_time', time());

		// generate link
		$link = elgg_generate_url('account:password:change', [
			'u' => $user_guid,
			'c' => $code,
		]);
		$link = _elgg_services()->urlSigner->sign($link, '+1 day');

		// generate email
		$ip_address = _elgg_services()->request->getClientIp();
		$message = _elgg_services()->translator->translate(
			'email:changereq:body', [$user->name, $ip_address, $link], $user->language);
		$subject = _elgg_services()->translator->translate(
			'email:changereq:subject', [], $user->language);

		$params = [
			'action' => 'requestnewpassword',
			'object' => $user,
			'ip_address' => $ip_address,
			'link' => $link,
		];
		
		return notify_user($user->guid, elgg_get_site_entity()->guid, $subject, $message, $params, 'email');
	}

	/**
	 * Set a user's new password and save the entity.
	 *
	 * This can only be called from execute_new_password_request().
	 *
	 * @param \ElggUser|int $user     The user GUID or entity
	 * @param string        $password Text (which will then be converted into a hash and stored)
	 *
	 * @return bool
	 */
	function forcePasswordReset($user, $password) {
		if (!$user instanceof \ElggUser) {
			$user = _elgg_services()->entityTable->get($user, 'user');
			if (!$user) {
				return false;
			}
		}

		$user->setPassword($password);

		$ia = _elgg_services()->session->setIgnoreAccess(true);
		$result = (bool) $user->save();
		_elgg_services()->session->setIgnoreAccess($ia);

		return $result;
	}

	/**
	 * Validate and change password for a user.
	 *
	 * @param int    $user_guid The user id
	 * @param string $conf_code Confirmation code as sent in the request email.
	 * @param string $password  Optional new password, if not randomly generated.
	 *
	 * @return bool True on success
	 */
	function executeNewPasswordReset($user_guid, $conf_code, $password = null) {
		$user_guid = (int) $user_guid;
		$user = get_entity($user_guid);

		if ($password === null) {
			$password = generate_random_cleartext_password();
			$reset = true;
		} else {
			$reset = false;
		}

		if (!$user instanceof \ElggUser) {
			return false;
		}

		$saved_code = $user->getPrivateSetting('passwd_conf_code');
		$code_time = (int) $user->getPrivateSetting('passwd_conf_time');
		$codes_match = _elgg_services()->crypto->areEqual($saved_code, $conf_code);

		if (!$saved_code || !$codes_match) {
			return false;
		}

		// Discard for security if it is 24h old
		if (!$code_time || $code_time < time() - 24 * 60 * 60) {
			return false;
		}

		if (!$this->forcePasswordReset($user, $password)) {
			return false;
		}

		$user->removePrivateSetting('passwd_conf_code');
		$user->removePrivateSetting('passwd_conf_time');
		
		// clean the logins failures
		reset_login_failure_count($user_guid);

		$ns = $reset ? 'resetpassword' : 'changepassword';

		$message = _elgg_services()->translator->translate(
			"email:$ns:body", [$user->username, $password], $user->language);
		$subject = _elgg_services()->translator->translate("email:$ns:subject", [], $user->language);

		$params = [
			'action' => $ns,
			'object' => $user,
			'password' => $password,
		];

		notify_user($user->guid, elgg_get_site_entity()->guid, $subject, $message, $params, 'email');

		return true;
	}
}
