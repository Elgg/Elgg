<?php

namespace Elgg\Database;

use Elgg\Cache\EntityCache;
use Elgg\Config as Conf;
use Elgg\Database;
use Elgg\Database\EntityTable;
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
	 * @var EntityTable
	 */
	protected $entities;

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
	 * @param EntityTable   $entities Entity table
	 * @param EntityCache   $cache    Entity cache
	 * @param EventsService $events   Event service
	 */
	public function __construct(
	Conf $config, Database $db, EntityTable $entities, EntityCache $cache, EventsService $events
	) {
		$this->config = $config;
		$this->db = $db;
		$this->table = $this->db->prefix . "users_entity";
		$this->entities = $entities;
		$this->entity_cache = $cache;
		$this->events = $events;
	}

	/**
	 * Return the user specific details of a user by a row.
	 *
	 * @param int $guid The \ElggUser guid
	 *
	 * @return mixed
	 * @access private
	 */
	public function getRow($guid) {
		$sql = "
			SELECT * FROM {$this->table}
			WHERE guid = :guid
		";
		$params = [
			':guid' => $guid,
		];
		return $this->db->getDataRow($sql, null, $params);
	}

	/**
	 * Disables all of a user's entities
	 *
	 * @param int $owner_guid The owner GUID
	 * @return bool Depending on success
	 * @deprecated 2.3
	 */
	public function disableEntities($owner_guid) {
		return $this->entities->disableEntities($owner_guid);
	}

	/**
	 * Ban a user (calls events, stores the reason)
	 *
	 * @param int    $user_guid The user guid
	 * @param string $reason    A reason
	 * @return bool
	 */
	public function ban($user_guid, $reason = "") {

		$user = get_entity($user_guid);

		if (!$user instanceof ElggUser || !$user->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('ban', 'user', $user)) {
			return false;
		}

		create_metadata($user_guid, 'ban_reason', $reason, '', 0, ACCESS_PUBLIC);

		_elgg_invalidate_cache_for_entity($user_guid);
		_elgg_invalidate_memcache_for_entity($user_guid);

		if ($this->markBanned($user_guid, true)) {
			return true;
		}

		return false;
	}

	/**
	 * Mark a user entity banned or unbanned.
	 *
	 * @note Use ban() or unban()
	 *
	 * @param int  $guid   User GUID
	 * @param bool $banned Mark the user banned?
	 * @return int Num rows affected
	 */
	public function markBanned($guid, $banned) {

		$query = "
			UPDATE {$this->table}
			SET banned = :banned
			WHERE guid = :guid
		";

		$params = [
			':banned' => $banned ? 'yes' : 'no',
			':guid' => (int) $guid,
		];

		return $this->db->updateData($query, true, $params);
	}

	/**
	 * Unban a user (calls events, removes the reason)
	 *
	 * @param int $user_guid Unban a user
	 * @return bool
	 */
	public function unban($user_guid) {

		$user = get_entity($user_guid);

		if (!$user instanceof ElggUser || !$user->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('unban', 'user', $user)) {
			return false;
		}

		create_metadata($user_guid, 'ban_reason', '', '', 0, ACCESS_PUBLIC);

		_elgg_invalidate_cache_for_entity($user_guid);
		_elgg_invalidate_memcache_for_entity($user_guid);

		return $this->markBanned($user_guid, false);
	}

	/**
	 * Makes user $guid an admin.
	 *
	 * @param int $user_guid User guid
	 * @return bool
	 */
	public function makeAdmin($user_guid) {
		$user = get_entity($user_guid);

		if (!$user instanceof ElggUser || !$user->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('make_admin', 'user', $user)) {
			return false;
		}

		$query = "
			UPDATE {$this->table}
			SET admin = 'yes'
			WHERE guid = :guid
		";

		$params = [
			':guid' => (int) $user_guid,
		];

		_elgg_invalidate_cache_for_entity($user_guid);
		_elgg_invalidate_memcache_for_entity($user_guid);

		if ($this->db->updateData($query, true, $params)) {
			return true;
		}

		return false;
	}

	/**
	 * Removes user $guid's admin flag.
	 *
	 * @param int $user_guid User GUID
	 * @return bool
	 */
	public function removeAdmin($user_guid) {

		$user = get_entity($user_guid);

		if (!$user instanceof ElggUser || !$user->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('remove_admin', 'user', $user)) {
			return false;
		}

		$query = "
			UPDATE {$this->table}
			SET admin = 'no'
			WHERE guid = :guid
		";

		$params = [
			':guid' => (int) $user_guid,
		];

		_elgg_invalidate_cache_for_entity($user_guid);
		_elgg_invalidate_memcache_for_entity($user_guid);

		if ($this->db->updateData($query, true, $params)) {
			return true;
		}

		return false;
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

		$users = $this->entities->getEntitiesFromAttributes([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'username',
				'value' => $username,
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

		$users = $this->entities->getEntitiesFromAttributes([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'email',
				'value' => $email,
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
	 *   Formerly this was the seconds parameter.
	 *
	 * @param int   $limit   Limit (deprecated usage, use $options)
	 * @param int   $offset  Offset (deprecated usage, use $options)
	 * @param bool  $count   Count (deprecated usage, use $options)
	 *
	 * @return ElggUser[]|int
	 */
	public function findActive($options = array(), $limit = 10, $offset = 0, $count = false) {

		$seconds = 600; //default value

		if (!is_array($options)) {
			elgg_deprecated_notice("find_active_users() now accepts an \$options array", 1.9);
			if (!$options) {
				$options = $seconds; //assign default value
			}
			$options = array('seconds' => $options);
		}

		if ($limit === null) {
			$limit = $this->config->get('default_limit');
		}

		$options = array_merge(array(
			'seconds' => $seconds,
			'limit' => $limit,
			'offset' => $offset,
			'count' => $count,
		), $options);

		// cast options we're sending to hook
		foreach (array('seconds', 'limit', 'offset') as $key) {
			$options[$key] = (int) $options[$key];
		}
		$options['count'] = (bool) $options['count'];

		// allow plugins to override
		$params = array(
			'seconds' => $options['seconds'],
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'count' => $options['count'],
			'options' => $options,
		);
		$data = _elgg_services()->hooks->trigger('find_active_users', 'system', $params, null);
		// check null because the handler could legitimately return falsey values.
		if ($data !== null) {
			return $data;
		}

		$dbprefix = $this->config->get('dbprefix');
		$time = $this->getCurrentTime()->getTimestamp() - $options['seconds'];
		return elgg_get_entities(array(
			'type' => 'user',
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'count' => $options['count'],
			'joins' => array("join {$dbprefix}users_entity u on e.guid = u.guid"),
			'wheres' => array("u.last_action >= {$time}"),
			'order_by' => "u.last_action desc",
		));
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
	 *
	 * @return int|false The new user's GUID; false on failure
	 * @throws RegistrationException
	 */
	public function register($username, $password, $name, $email, $allow_multiple_emails = false) {

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
		$user = new ElggUser();
		$user->username = $username;
		$user->email = $email;
		$user->name = $name;
		$user->access_id = ACCESS_PUBLIC;
		$user->setPassword($password);
		$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
		$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
		$user->language = _elgg_services()->translator->getCurrentLanguage();
		if ($user->save() === false) {
			return false;
		}

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
		return "$time." . _elgg_services()->crypto->getHmac([(int) $time, $username])->getToken();
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

		return _elgg_services()->crypto->getHmac([(int) $time, $username])->matchesToken($mac);
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
		$result1 = create_metadata($user_guid, 'validated', $status, '', 0, ACCESS_PUBLIC, false);
		$result2 = create_metadata($user_guid, 'validated_method', $method, '', 0, ACCESS_PUBLIC, false);
		if ($result1 && $result2) {
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

		$query = "
			UPDATE {$this->table}
			SET
				prev_last_action = last_action,
				last_action = :last_action
			WHERE guid = :guid
		";

		$params = [
			':last_action' => $time,
			':guid' => (int) $user->guid,
		];

		$user->prev_last_action = $user->last_action;
		$user->last_action = $time;

		execute_delayed_write_query($query, null, $params);

		$this->entity_cache->set($user);

		// If we save the user to memcache during this request, then we'll end up with the
		// old (incorrect) attributes cached (notice the above query is delayed). So it's
		// simplest to just resave the user after all plugin code runs.
		register_shutdown_function(function () use ($user, $time) {
			$this->entities->updateLastAction($user, $time); // keep entity table in sync
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

		$query = "
			UPDATE {$this->table}
			SET
				prev_last_login = last_login,
				last_login = :last_login
			WHERE guid = :guid
		";

		$params = [
			':last_login' => $time,
			':guid' => (int) $user->guid,
		];

		$user->prev_last_login = $user->last_login;
		$user->last_login = $time;

		execute_delayed_write_query($query, null, $params);

		$this->entity_cache->set($user);

		// If we save the user to memcache during this request, then we'll end up with the
		// old (incorrect) attributes cached. Hence we want to invalidate as late as possible.
		// the user object gets saved
		register_shutdown_function(function () use ($user) {
			$user->storeInPersistedCache(_elgg_get_memcache('new_entity_cache'));
		});
	}

}
