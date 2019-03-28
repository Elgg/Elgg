<?php

namespace Elgg\Database;

use Elgg\Config as Conf;
use Elgg\Database;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Validation\ValidationResults;
use ElggUser;
use Exception;
use RegistrationException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class UsersTable {

	use \Elgg\TimeUsing;

	/**
	 * @var Conf
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
	 * @param Conf          $config   Config
	 * @param Database      $db       Database
	 * @param MetadataTable $metadata Metadata table
	 */
	public function __construct(Conf $config, Database $db, MetadataTable $metadata) {
		$this->config = $config;
		$this->db = $db;
		$this->metadata = $metadata;
	}

	/**
	 * Get user by username
	 *
	 * @param string $username The user's username
	 *
	 * @return ElggUser|false Depending on success
	 */
	public function getByUsername($username) {

		// Fixes #6052. Username is frequently sniffed from the path info, which,
		// unlike $_GET, is not URL decoded. If the username was not URL encoded,
		// this is harmless.
		$username = rawurldecode($username);

		if (!$username) {
			return false;
		}

		$entity =_elgg_services()->dataCache->usernames->load($username);
		if ($entity instanceof ElggUser) {
			return $entity;
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
	 * @return array
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
	 *
	 *   seconds (int)  => Length of period (default 600 = 10min)
	 *   limit   (int)  => Limit (default 10)
	 *   offset  (int)  => Offset (default 0)
	 *   count   (bool) => Return a count instead of users? (default false)
	 *
	 * @return \ElggUser[]|int
	 */
	public function findActive(array $options = []) {

		$options = array_merge([
			'seconds' => 600,
			'limit' => $this->config->default_limit,
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
	 * Registers a user, returning false if the username already exists
	 *
	 * @param string $username              The username of the new user
	 * @param string $password              The password
	 * @param string $name                  The user's display name
	 * @param string $email                 The user's email address
	 * @param bool   $allow_multiple_emails Allow the same email address to be
	 *                                      registered multiple times?
	 * @param string $subtype               Subtype of the user entity
	 *
	 * @return int|false The new user's GUID; false on failure
	 * @throws RegistrationException
	 * @deprecated 3.0 Use elgg()->accounts->register()
	 */
	public function register($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null) {
		_elgg_services()->accounts->register($username, $password, $name, $email, $allow_multiple_emails, $subtype);
	}

	/**
	 * Generates a unique invite code for a user
	 *
	 * @param string $username The username of the user sending the invitation
	 *
	 * @return string Invite code
	 * @see validateInviteCode
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
	 * @see generateInviteCode
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