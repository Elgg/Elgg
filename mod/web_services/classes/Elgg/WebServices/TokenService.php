<?php

namespace Elgg\WebServices;

class TokenService {

	/**
	 * Default token validity in minutes
	 */
	const DEFAULT_EXPIRES = 60;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dbprefix = elgg_get_config('dbprefix');
	}

	/**
	 * Obtain a token for a user
	 *
	 * @param \ElggUser $user   User entity
	 * @param \ElggSite $site   Site entity token applies to
	 * @param int       $expire Minutes until token expires (default is 60 minutes)
	 * @return \Elgg\WebServices\UserToken|false
	 */
	public function create(\ElggUser $user, \ElggSite $site, $expire = self::DEFAULT_EXPIRES) {

		$time = time();
		$time += 60 * $expire;
		$token = md5(rand() . microtime() . $user->username . $time . $site->guid);

		$result = insert_data("INSERT into {$this->dbprefix}users_apisessions
				(user_guid, site_guid, token, expires) values
				({$user->guid}, {$site->guid}, '{$token}', '{$time}')
				on duplicate key update token='{$token}', expires='{$time}'");

		if (!$result) {
			return false;
		}

		return \Elgg\WebServices\UserToken::load($token);
	}

	/**
	 * Get all tokens attached to a user
	 *
	 * @param \ElggUser $user User entity
	 * @param \ElggSite $site Site entity
	 * @return \Elgg\WebServices\UserToken[]|false
	 */
	public function all(\ElggUser $user, \ElggSite $site = null) {

		$query = "SELECT * FROM {$this->dbprefix}users_apisessions WHERE user_guid={$user->guid}";

		if ($site) {
			$query .= " AND site_guid={$site->guid}";
		}

		$tokens = get_data($query);

		if (empty($tokens)) {
			return false;
		}

		array_walk($tokens, function($row) {
			return new Elgg\WebServices\UserToken($row);
		});

		return $tokens;
	}

	/**
	 * Remove expired tokens
	 * @return bool
	 */
	function removeExpiredTokens() {
		$time = time();
		return delete_data("DELETE FROM {$this->dbprefix}users_apisessions
								WHERE expires < $time");
	}

	/**
	 * Exchange username and password for a token
	 *
	 * @param array $credentials Values received with the API request
	 * @return \Elgg\WebServices\UserToken|false
	 * @throws SecurityException
	 * @access private
	 */
	public function exchange(array $credentials = array()) {

		$username = elgg_extract('username', $credentials);
		$password = elgg_extract('password', $credentials);

		// check if username is an email address
		if (is_email_address($username)) {
			$users = get_user_by_email($username);

			// check if we have a unique user
			if (is_array($users) && (count($users) == 1)) {
				$username = $users[0]->username;
			}
		}

		// validate username and password
		$pam = new \ElggPAM('user');
		$result = $pam->authenticate(array(
			'username' => $username,
			'password' => $password
		));

		if (true === $result) {
			$user = get_user_by_username($username);
			$site = elgg_get_site_entity();
			if (!$user || !$site) {
				return false;
			}
			$token = $this->create($user, $site);
			if ($token) {
				return $token->token;
			}
		}

		throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
	}

}
