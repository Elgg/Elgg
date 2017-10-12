<?php

namespace Elgg\Database;

use Elgg\Cache\EntityCache;
use Elgg\Config as Conf;
use Elgg\Database;
use Elgg\Database\MetadataTable;
use Elgg\EventsService;
use ElggUser;
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
	 * @var EntityCache
	 */
	protected $entity_cache;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * Constructor
	 *
	 * @param Conf          $config   Config
	 * @param Database      $db       Database
	 * @param MetadataTable $metadata Metadata table
	 * @param EntityCache   $cache    Entity cache
	 * @param EventsService $events   Event service
	 */
	public function __construct(
	Conf $config, Database $db, MetadataTable $metadata, EntityCache $cache, EventsService $events
	) {
		$this->config = $config;
		$this->db = $db;
		$this->metadata = $metadata;
		$this->entity_cache = $cache;
		$this->events = $events;
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

		$entity = $this->entity_cache->getByUsername($username);
		if ($entity) {
			return $entity;
		}

		$users = $this->metadata->getEntities([
			'types' => 'user',
			'metadata_name_value_pairs' => [
				[
					'name' => 'username',
					'value' => $username,
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
		
		$users = $this->metadata->getEntities([
			'types' => 'user',
			'metadata_name_value_pairs' => [
				[
					'name' => 'email',
					'value' => $email,
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

		$dbprefix = $this->config->dbprefix;
		$time = $this->getCurrentTime()->getTimestamp() - $options['seconds'];
		return elgg_get_entities([
			'type' => 'user',
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'count' => $options['count'],
			'wheres' => ["e.last_action >= {$time}"],
			'order_by' => "e.last_action desc",
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
	 */
	public function register($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null) {

		// no need to trim password
		$username = trim($username);
		$name = trim(strip_tags($name));
		$email = trim($email);

		// A little sanity checking
		if (empty($username) || empty($password) || empty($name) || empty($email)) {
			return false;
		}

		// Make sure a user with conflicting details hasn't registered and been disabled
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		if (!validate_email_address($email)) {
			throw new RegistrationException(_elgg_services()->translator->translate('registration:emailnotvalid'));
		}

		if (!validate_password($password)) {
			throw new RegistrationException(_elgg_services()->translator->translate('registration:passwordnotvalid'));
		}

		if (!validate_username($username)) {
			throw new RegistrationException(_elgg_services()->translator->translate('registration:usernamenotvalid'));
		}

		if ($user = get_user_by_username($username)) {
			throw new RegistrationException(_elgg_services()->translator->translate('registration:userexists'));
		}

		if ((!$allow_multiple_emails) && (get_user_by_email($email))) {
			throw new RegistrationException(_elgg_services()->translator->translate('registration:dupeemail'));
		}

		access_show_hidden_entities($access_status);

		// Create user
		$constructor = ElggUser::class;
		if ($subtype) {
			$class = get_subtype_class('user', $subtype);
			if ($class && class_exists($class) && is_subclass_of($class, ElggUser::class)) {
				$constructor = $class;
			}
		}

		$user = new $constructor();
		$user->subtype = $subtype;
		$user->username = $username;
		$user->email = $email;
		$user->name = $name;
		$user->access_id = ACCESS_PUBLIC;
		$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
		$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
		$user->language = _elgg_services()->translator->getCurrentLanguage();
		if ($user->save() === false) {
			return false;
		}
		
		// doing this after save to prevent metadata save notices on unwritable metadata password_hash
		$user->setPassword($password);

		// Turn on email notifications by default
		$user->setNotificationSetting('email', true);
	
		return $user->getGUID();
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

	/**
	 * Set the validation status for a user.
	 *
	 * @param int    $user_guid The user's GUID
	 * @param bool   $status    Validated (true) or unvalidated (false)
	 * @param string $method    Optional method to say how a user was validated
	 * @return bool
	 */
	public function setValidationStatus($user_guid, $status, $method = '') {
		$user = get_user($user_guid);
		if (!$user) {
			return false;
		}

		$result1 = create_metadata($user->guid, 'validated', (int) $status);
		$result2 = create_metadata($user->guid, 'validated_method', $method);
		if ($result1 && $result2) {
			if ((bool) $status) {
				elgg_trigger_after_event('validate', 'user', $user);
			} else {
				elgg_trigger_after_event('invalidate', 'user', $user);
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Gets the validation status of a user.
	 *
	 * @param int $user_guid The user's GUID
	 * @return bool|null Null means status was not set for this user.
	 */
	public function getValidationStatus($user_guid) {
		$user = get_entity($user_guid);
		if (!$user || !isset($user->validated)) {
			return null;
		}
		return (bool) $user->validated;
	}

	/**
	 * Sets the last action time of the given user to right now.
	 *
	 * @see _elgg_session_boot The session boot calls this at the beginning of every request
	 *
	 * @param ElggUser $user User entity
	 * @return void
	 */
	public function setLastAction(ElggUser $user) {

		$time = $this->getCurrentTime()->getTimestamp();

		if ($user->last_action == $time) {
			// no change required
			return;
		}

		// these writes actually work, we just type hint read-only.
		$user->prev_last_action = $user->last_action;
		$user->last_action = $time;
		
		register_shutdown_function(function () use ($user, $time) {
			$user->updateLastAction($user, $time); // keep entity table in sync
			$user->storeInPersistedCache(_elgg_get_memcache('new_entity_cache'), $time);
		});
	}

	/**
	 * Sets the last logon time of the given user to right now.
	 *
	 * @param ElggUser $user User entity
	 * @return void
	 */
	public function setLastLogin(ElggUser $user) {

		$time = $this->getCurrentTime()->getTimestamp();

		if ($user->last_login == $time) {
			// no change required
			return;
		}

		// these writes actually work, we just type hint read-only.
		$user->prev_last_login = $user->last_login;
		$user->last_login = $time;
	}
}
