<?php

namespace Elgg\Database;

use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Traits\TimeUsing;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 *
 * @since 1.10.0
 */
class UsersTable {

	use TimeUsing;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var MetadataTable
	 */
	protected $metadata;

	/**
	 * Constructor
	 *
	 * @param Config        $config   Config
	 * @param Database      $db       Database
	 * @param MetadataTable $metadata Metadata table
	 */
	public function __construct(Config $config, Database $db, MetadataTable $metadata) {
		$this->config = $config;
		$this->db = $db;
		$this->metadata = $metadata;
	}

	/**
	 * Get user by username
	 *
	 * @param string $username The user's username
	 *
	 * @return \ElggUser|false Depending on success
	 */
	public function getByUsername($username) {

		// Fixes #6052. Username is frequently sniffed from the path info, which,
		// unlike $_GET, is not URL decoded. If the username was not URL encoded,
		// this is harmless.
		$username = rawurldecode($username);

		if (!$username) {
			return false;
		}

		$logged_in_user = elgg_get_logged_in_user_entity();
		if (!empty($logged_in_user) && ($logged_in_user->username === $username)) {
			return $logged_in_user;
		}

		$users = elgg_get_entities([
			'types' => 'user',
			'metadata_name_value_pairs' => [
				[
					'name' => 'username',
					'value' => $username,
					'case_sensitive' => false,
				],
			],
			'limit' => 1,
		]);

		return $users ? $users[0] : false;
	}

	/**
	 * Get an array of users from an email address
	 *
	 * @param string $email Email address
	 * @return \ElggUser[]
	 */
	public function getByEmail($email) {
		if (!$email) {
			return [];
		}

		$users = elgg_get_entities([
			'types' => 'user',
			'metadata_name_value_pairs' => [
				[
					'name' => 'email',
					'value' => $email,
					'case_sensitive' => false,
				],
			],
			'limit' => 1,
		]);

		return $users ? : [];
	}

	/**
	 * Return users (or the number of them) who have been active within a recent period.
	 *
	 * @param array $options Array of options with keys:
	 *                       - seconds (int)  => Length of period (default 600 = 10min)
	 *                       - limit   (int)  => Limit (default 10)
	 *                       - offset  (int)  => Offset (default 0)
	 *                       - count   (bool) => Return a count instead of users? (default false)
	 *
	 * @return \ElggUser[]|int
	 */
	public function findActive(array $options = []) {

		$options = array_merge([
			'seconds' => 600,
			'limit' => $this->config->default_limit,
			'offset' => 0,
		], $options);

		// cast options we're sending to hook
		foreach (['seconds', 'limit', 'offset'] as $key) {
			$options[$key] = (int) $options[$key];
		}
		$options['count'] = (bool) $options['count'];

		// allow plugins to override
		$params = [
			'seconds' => $options['seconds'],
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'count' => $options['count'],
			'options' => $options,
		];
		$data = _elgg_services()->hooks->trigger('find_active_users', 'system', $params, null);
		// check null because the handler could legitimately return falsey values.
		if ($data !== null) {
			return $data;
		}

		$time = $this->getCurrentTime()->getTimestamp() - $options['seconds'];
		return elgg_get_entities([
			'type' => 'user',
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'count' => $options['count'],
			'wheres' => function(QueryBuilder $qb, $main_alias) use ($time) {
				return $qb->compare("{$main_alias}.last_action", '>=', $time, ELGG_VALUE_INTEGER);
			},
			'order_by' => new OrderByClause('e.last_action', 'DESC'),
		]);
	}

	/**
	 * Generates a unique invite code for a user
	 *
	 * @param string $username The username of the user sending the invitation
	 *
	 * @return string Invite code
	 * @see self::validateInviteCode()
	 */
	public function generateInviteCode($username) {
		$time = $this->getCurrentTime()->getTimestamp();
		return "$time." . _elgg_services()->hmac->getHmac([(int) $time, $username])->getToken();
	}

	/**
	 * Validate a user's invite code
	 *
	 * @param string $username The username
	 * @param string $code     The invite code
	 *
	 * @return bool
	 * @see self::generateInviteCode()
	 */
	public function validateInviteCode($username, $code) {
		// validate the format of the token created by ->generateInviteCode()
		if (!preg_match('~^(\d+)\.([a-zA-Z0-9\-_]+)$~', $code, $m)) {
			return false;
		}
		$time = $m[1];
		$mac = $m[2];

		return _elgg_services()->hmac->getHmac([(int) $time, $username])->matchesToken($mac);
	}
}
