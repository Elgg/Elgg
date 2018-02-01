<?php

namespace Elgg\Database;

use Elgg\Config;
use Elgg\Database;
use Elgg\Database\EntityTable\UserFetchFailureException;
use Elgg\I18n\Translator;
use Elgg\PluginHooksService;
use Elgg\UserCapabilities;
use ElggCache;
use ElggEntity;
use ElggSession;
use ElggUser;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class AccessCollections {

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @vars ElggCache
	 */
	protected $access_cache;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var UserCapabilities
	 */
	protected $capabilities;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var string
	 */
	protected $membership_table;

	/**
	 * @var bool
	 */
	protected $init_complete = false;

	/**
	 * Constructor
	 *
	 * @param Config             $config       Config
	 * @param Database           $db           Database
	 * @param EntityTable        $entities     Entity table
	 * @param UserCapabilities   $capabilities User capabilities
	 * @param ElggCache          $cache        Access cache
	 * @param PluginHooksService $hooks        Hooks
	 * @param ElggSession        $session      Session
	 * @param Translator         $translator   Translator
	 */
	public function __construct(
		Config $config,
		Database $db,
		EntityTable $entities,
		UserCapabilities $capabilities,
		ElggCache $cache,
		PluginHooksService $hooks,
		ElggSession $session,
		Translator $translator) {
		$this->config = $config;
		$this->db = $db;
		$this->entities = $entities;
		$this->capabilities = $capabilities;
		$this->access_cache = $cache;
		$this->hooks = $hooks;
		$this->session = $session;
		$this->translator = $translator;

		$this->table = "{$this->db->prefix}access_collections";
		$this->membership_table = "{$this->db->prefix}access_collection_membership";
	}

	/**
	 * Mark the access system as initialized
	 *
	 * @return void
	 */
	public function markInitComplete() {
		$this->init_complete = true;
	}

	/**
	 * Returns a string of access_ids for $user_guid appropriate for inserting into an SQL IN clause.
	 *
	 * @see get_access_array()
	 *
	 * @param int  $user_guid User ID; defaults to currently logged in user
	 * @param bool $flush     If set to true, will refresh the access list from the
	 *                        database rather than using this function's cache.
	 *
	 * @return string A concatenated string of access collections suitable for using in an SQL IN clause
	 * @access private
	 */
	public function getAccessList($user_guid = 0, $flush = false) {
		$access_array = $this->getAccessArray($user_guid, $flush);
		$access_ids = implode(',', $access_array);
		$list = "($access_ids)";

		// for BC, populate the cache
		$hash = $user_guid . 'get_access_list';
		$this->access_cache->add($hash, $list);

		return $list;
	}

	/**
	 * Returns an array of access IDs a user is permitted to see.
	 *
	 * Can be overridden with the 'access:collections:read', 'user' plugin hook.
	 * @warning A callback for that plugin hook needs to either not retrieve data
	 * from the database that would use the access system (triggering the plugin again)
	 * or ignore the second call. Otherwise, an infinite loop will be created.
	 *
	 * This returns a list of all the collection ids a user owns or belongs
	 * to plus public and logged in access levels. If the user is an admin, it includes
	 * the private access level.
	 *
	 * @internal this is only used in core for creating the SQL where clause when
	 * retrieving content from the database. The friends access level is handled by
	 * _elgg_get_access_where_sql().
	 *
	 * @see get_write_access_array() for the access levels that a user can write to.
	 *
	 * @param int  $user_guid User ID; defaults to currently logged in user
	 * @param bool $flush     If set to true, will refresh the access ids from the
	 *                        database rather than using this function's cache.
	 *
	 * @return array An array of access collections ids
	 */
	public function getAccessArray($user_guid = 0, $flush = false) {
		$cache = $this->access_cache;

		if ($flush) {
			$cache->clear();
		}

		if ($user_guid == 0) {
			$user_guid = $this->session->getLoggedInUserGuid();
		}

		$user_guid = (int) $user_guid;

		$hash = $user_guid . 'get_access_array';

		if ($cache[$hash]) {
			$access_array = $cache[$hash];
		} else {
			// Public access is always visible
			$access_array = [ACCESS_PUBLIC];

			// The following can only return sensible data for a known user.
			if ($user_guid) {
				$access_array[] = ACCESS_LOGGED_IN;

				// Get ACLs that user owns or is a member of
				$query = "
					SELECT ac.id
					FROM {$this->table} ac
					WHERE ac.owner_guid = :user_guid
					OR EXISTS (SELECT 1
							   FROM {$this->membership_table}
							   WHERE access_collection_id = ac.id
							   AND user_guid = :user_guid)
				";

				$collections = $this->db->getData($query, null, [
					':user_guid' => $user_guid,
				]);

				if ($collections) {
					foreach ($collections as $collection) {
						$access_array[] = (int) $collection->id;
					}
				}

				$ignore_access = $this->capabilities->canBypassPermissionsCheck($user_guid);

				if ($ignore_access == true) {
					$access_array[] = ACCESS_PRIVATE;
				}
			}

			if ($this->init_complete) {
				$cache[$hash] = $access_array;
			}
		}

		$options = [
			'user_id' => $user_guid,
		];

		// see the warning in the docs for this function about infinite loop potential
		return $this->hooks->trigger('access:collections:read', 'user', $options, $access_array);
	}

	/**
	 * Can a user access an entity.
	 *
	 * @warning If a logged in user doesn't have access to an entity, the
	 * core engine will not load that entity.
	 *
	 * @tip This is mostly useful for checking if a user other than the logged in
	 * user has access to an entity that is currently loaded.
	 *
	 * @todo This function would be much more useful if we could pass the guid of the
	 * entity to test access for. We need to be able to tell whether the entity exists
	 * and whether the user has access to the entity.
	 *
	 * @param ElggEntity $entity The entity to check access for.
	 * @param ElggUser   $user   Optionally user to check access for. Defaults to
	 *                           logged in user (which is a useless default).
	 *
	 * @return bool
	 */
	public function hasAccessToEntity($entity, $user = null) {
		if (!$entity instanceof \ElggEntity) {
			return false;
		}

		if ($entity->access_id == ACCESS_PUBLIC) {
			// Public entities are always accessible
			return true;
		}

		$user_guid = isset($user) ? (int) $user->guid : _elgg_services()->session->getLoggedInUserGuid();

		if ($user_guid && $user_guid == $entity->owner_guid) {
			// Owners have access to their own content
			return true;
		}

		if ($user_guid && $entity->access_id == ACCESS_LOGGED_IN) {
			// Existing users have access to entities with logged in access
			return true;
		}

		// See #7159. Must not allow ignore access to affect query
		$ia = _elgg_services()->session->setIgnoreAccess(false);

		$row = $this->entities->getRow($entity->guid, $user_guid);

		_elgg_services()->session->setIgnoreAccess($ia);

		return !empty($row);
	}

	/**
	 * Returns an array of access permissions that the user is allowed to save content with.
	 * Permissions returned are of the form (id => 'name').
	 *
	 * Example return value in English:
	 * array(
	 *     0 => 'Private',
	 *    -2 => 'Friends',
	 *     1 => 'Logged in users',
	 *     2 => 'Public',
	 *    34 => 'My favorite friends',
	 * );
	 *
	 * Plugin hook of 'access:collections:write', 'user'
	 *
	 * @warning this only returns access collections that the user owns plus the
	 * standard access levels. It does not return access collections that the user
	 * belongs to such as the access collection for a group.
	 *
	 * @param int   $user_guid    The user's GUID.
	 * @param bool  $flush        If this is set to true, this will ignore a cached access array
	 * @param array $input_params Some parameters passed into an input/access view
	 *
	 * @return array List of access permissions
	 */
	public function getWriteAccessArray($user_guid = 0, $flush = false, array $input_params = []) {
		$cache = $this->access_cache;

		if ($flush) {
			$cache->clear();
		}

		if ($user_guid == 0) {
			$user_guid = $this->session->getLoggedInUserGuid();
		}

		$user_guid = (int) $user_guid;

		$hash = $user_guid . 'get_write_access_array';

		if ($cache[$hash]) {
			$access_array = $cache[$hash];
		} else {
			$access_array = [
				ACCESS_PRIVATE => $this->getReadableAccessLevel(ACCESS_PRIVATE),
				ACCESS_LOGGED_IN => $this->getReadableAccessLevel(ACCESS_LOGGED_IN),
				ACCESS_PUBLIC => $this->getReadableAccessLevel(ACCESS_PUBLIC)
			];

			$collections = $this->getEntityCollections(['owner_guid' => $user_guid]);
			if ($collections) {
				foreach ($collections as $collection) {
					$access_array[$collection->id] = $collection->getDisplayName();
				}
			}

			if ($this->init_complete) {
				$cache[$hash] = $access_array;
			}
		}

		$options = [
			'user_id' => $user_guid,
			'input_params' => $input_params,
		];
		
		$access_array = $this->hooks->trigger('access:collections:write', 'user', $options, $access_array);
		
		// move logged in and public to the end of the array
		foreach ([ACCESS_LOGGED_IN, ACCESS_PUBLIC] as $access) {
			if (!isset($access_array[$access])) {
				continue;
			}
		
			$temp = $access_array[$access];
			unset($access_array[$access]);
			$access_array[$access] = $temp;
		}
		
		
		return $access_array;
	}

	/**
	 * Can the user change this access collection?
	 *
	 * Use the plugin hook of 'access:collections:write', 'user' to change this.
	 * @see get_write_access_array() for details on the hook.
	 *
	 * Respects access control disabling for admin users and {@link elgg_set_ignore_access()}
	 *
	 * @see get_write_access_array()
	 *
	 * @param int   $collection_id The collection id
	 * @param mixed $user_guid     The user GUID to check for. Defaults to logged in user.
	 * @return bool
	 */
	public function canEdit($collection_id, $user_guid = null) {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$collection = $this->get($collection_id);

		if (!$user || !$collection) {
			return false;
		}

		if ($this->capabilities->canBypassPermissionsCheck($user->guid)) {
			return true;
		}

		$write_access = $this->getWriteAccessArray($user->guid, true);
		return array_key_exists($collection_id, $write_access);
	}

	/**
	 * Creates a new access collection.
	 *
	 * Access colletions allow plugins and users to create granular access
	 * for entities.
	 *
	 * Triggers plugin hook 'access:collections:addcollection', 'collection'
	 *
	 * @internal Access collections are stored in the access_collections table.
	 * Memberships to collections are in access_collections_membership.
	 *
	 * @param string $name       The name of the collection.
	 * @param int    $owner_guid The GUID of the owner (default: currently logged in user).
	 * @param string $subtype    The subtype indicates the usage of the acl
	 *
	 * @return int|false The collection ID if successful and false on failure.
	 */
	public function create($name, $owner_guid = 0, $subtype = null) {
		$name = trim($name);
		if (empty($name)) {
			return false;
		}

		if (isset($subtype)) {
			$subtype = trim($subtype);
			if (strlen($subtype) > 255) {
				_elgg_services()->logger->error("The subtype length for access collections cannot be greater than 255");
				return false;
			}
		}

		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}

		$query = "
			INSERT INTO {$this->table}
			SET name = :name,
				subtype = :subtype,
				owner_guid = :owner_guid
		";

		$params = [
			':name' => $name,
			':subtype' => $subtype,
			':owner_guid' => (int) $owner_guid,
		];

		$id = $this->db->insertData($query, $params);
		if (!$id) {
			return false;
		}

		$this->access_cache->clear();

		$hook_params = [
			'collection_id' => $id,
			'name' => $name,
			'subtype' => $subtype,
			'owner_guid' => $owner_guid,
		];

		if (!$this->hooks->trigger('access:collections:addcollection', 'collection', $hook_params, true)) {
			$this->delete($id);
			return false;
		}

		return $id;
	}

	/**
	 * Renames an access collection
	 *
	 * @param int    $collection_id ID of the collection
	 * @param string $name          The name of the collection
	 * @return bool
	 */
	public function rename($collection_id, $name) {

		$query = "
			UPDATE {$this->table}
			SET name = :name
			WHERE id = :id
		";

		$params = [
			':name' => $name,
			':id' => (int) $collection_id,
		];

		if ($this->db->insertData($query, $params)) {
			$this->access_cache->clear();
			return (int) $collection_id;
		}

		return false;
	}


	/**
	 * Updates the membership in an access collection.
	 *
	 * @warning Expects a full list of all members that should
	 * be part of the access collection
	 *
	 * @note This will run all hooks associated with adding or removing
	 * members to access collections.
	 *
	 * @param int   $collection_id ID of the collection.
	 * @param array $new_members   Array of member entities or GUIDs
	 * @return bool
	 */
	public function update($collection_id, array $new_members = []) {
		$acl = $this->get($collection_id);

		if (!$acl) {
			return false;
		}

		$to_guid = function($elem) {
			if (empty($elem)) {
				return 0;
			}
			if (is_object($elem)) {
				return (int) $elem->guid;
			}
			return (int) $elem;
		};

		$current_members = [];
		$new_members = array_map($to_guid, $new_members);

		$current_members_batch = $this->getMembers($collection_id, [
			'batch' => true,
			'limit' => 0,
			'callback' => false,
		]);

		foreach ($current_members_batch as $row) {
			$current_members[] = $to_guid($row);
		}

		$remove_members = array_diff($current_members, $new_members);
		$add_members = array_diff($new_members, $current_members);

		$result = true;

		foreach ($add_members as $guid) {
			$result = $result && $this->addUser($guid, $collection_id);
		}

		foreach ($remove_members as $guid) {
			$result = $result && $this->removeUser($guid, $collection_id);
		}

		$this->access_cache->clear();

		return $result;
	}

	/**
	 * Deletes a collection and its membership information
	 *
	 * @param int $collection_id ID of the collection
	 * @return bool
	 */
	public function delete($collection_id) {
		$collection_id = (int) $collection_id;

		$params = [
			'collection_id' => $collection_id,
		];

		if (!$this->hooks->trigger('access:collections:deletecollection', 'collection', $params, true)) {
			return false;
		}

		// Deleting membership doesn't affect result of deleting ACL.
		$query = "
			DELETE FROM {$this->membership_table}
			WHERE access_collection_id = :access_collection_id
		";
		$this->db->deleteData($query, [
			':access_collection_id' => $collection_id,
		]);

		$query = "
			DELETE FROM {$this->table}
			WHERE id = :id
		";
		$result = $this->db->deleteData($query, [
			':id' => $collection_id,
		]);

		$this->access_cache->clear();

		return (bool) $result;
	}

	/**
	 * Transforms a database row to an instance of ElggAccessCollection
	 *
	 * @param \stdClass $row Database row
	 * @return \ElggAccessCollection
	 */
	public function rowToElggAccessCollection(\stdClass $row) {
		return new \ElggAccessCollection($row);
	}

	/**
	 * Get a specified access collection
	 *
	 * @note This doesn't return the members of an access collection,
	 * just the database row of the actual collection.
	 *
	 * @see get_members_of_access_collection()
	 *
	 * @param int $collection_id The collection ID
	 * @return \ElggAccessCollection|false
	 */
	public function get($collection_id) {

		$callback = [$this, 'rowToElggAccessCollection'];

		$query = "
			SELECT * FROM {$this->table}
			WHERE id = :id
		";

		$result = $this->db->getDataRow($query, $callback, [
			':id' => (int) $collection_id,
		]);

		if (empty($result)) {
			return false;
		}

		return $result;
	}

	/**
	 * Check if user is already in the collection
	 *
	 * @param int $user_guid     GUID of the user
	 * @param int $collection_id ID of the collection
	 * @return bool
	 */
	public function hasUser($user_guid, $collection_id) {
		$options = [
			'guids' => (int) $user_guid,
			'count' => true,
		];
		return (bool) $this->getMembers($collection_id, $options);
	}

	/**
	 * Adds a user to an access collection.
	 *
	 * Triggers the 'access:collections:add_user', 'collection' plugin hook.
	 *
	 * @param int $user_guid     GUID of the user to add
	 * @param int $collection_id ID of the collection to add them to
	 * @return bool
	 */
	public function addUser($user_guid, $collection_id) {

		$collection = $this->get($collection_id);

		if (!$collection) {
			return false;
		}

		if (!$this->entities->exists($user_guid)) {
			return false;
		}

		$hook_params = [
			'collection_id' => $collection->id,
			'user_guid' => (int) $user_guid
		];

		$result = $this->hooks->trigger('access:collections:add_user', 'collection', $hook_params, true);
		if ($result == false) {
			return false;
		}

		// if someone tries to insert the same data twice, we do a no-op on duplicate key
		$query = "
			INSERT INTO {$this->membership_table}
				SET access_collection_id = :access_collection_id,
				    user_guid = :user_guid
				ON DUPLICATE KEY UPDATE user_guid = user_guid
		";

		$result = $this->db->insertData($query, [
			':access_collection_id' => (int) $collection->id,
			':user_guid' => (int) $user_guid,
		]);

		$this->access_cache->clear();

		return $result !== false;
	}

	/**
	 * Removes a user from an access collection.
	 *
	 * Triggers the 'access:collections:remove_user', 'collection' plugin hook.
	 *
	 * @param int $user_guid     GUID of the user
	 * @param int $collection_id ID of the collection
	 * @return bool
	 */
	public function removeUser($user_guid, $collection_id) {

		$params = [
			'collection_id' => (int) $collection_id,
			'user_guid' => (int) $user_guid,
		];

		if (!$this->hooks->trigger('access:collections:remove_user', 'collection', $params, true)) {
			return false;
		}

		$query = "
			DELETE FROM {$this->membership_table}
			WHERE access_collection_id = :access_collection_id
				AND user_guid = :user_guid
		";

		$this->access_cache->clear();

		return (bool) $this->db->deleteData($query, [
			':access_collection_id' => (int) $collection_id,
			':user_guid' => (int) $user_guid,
		]);
	}

	/**
	 * Returns access collections
	 *
	 * @param array $options Options to get access collections by
	 *                       Supported are 'owner_guid', 'subtype'
	 * @return \ElggAccessCollection[]
	 */
	public function getEntityCollections($options = []) {

		$callback = [$this, 'rowToElggAccessCollection'];

		$supported_options = ['owner_guid', 'subtype'];

		$wheres = [];
		$params = [];
		foreach ($supported_options as $option) {
			$option_value = elgg_extract($option, $options);
			if (!isset($option_value)) {
				continue;
			}
			$wheres[] = "{$option} = :{$option}";
			$params[":{$option}"] = $option_value;
		}

		$query = "SELECT * FROM {$this->table}";
		if (!empty($wheres)) {
			$query .= ' WHERE ' . implode(' AND ', $wheres);
		}
		$query .= ' ORDER BY name ASC';

		return $this->db->getData($query, $callback, $params);
	}

	/**
	 * Get members of an access collection
	 *
	 * @param int   $collection_id The collection's ID
	 * @param array $options       Ege* options
	 * @return ElggEntity[]|false
	 */
	public function getMembers($collection_id, array $options = []) {
		$options['wheres'][] = function(QueryBuilder $qb, $table_alias) use ($collection_id) {
			$qb->join($table_alias, 'access_collection_membership', 'acm', $qb->compare('acm.user_guid', '=', "$table_alias.guid"));
			return $qb->compare('acm.access_collection_id', '=', $collection_id, 'integer');
		};

		return Entities::find($options);
	}

	/**
	 * Return an array of collections that the entity is member of
	 *
	 * @param int $member_guid GUID of th member
	 *
	 * @return \ElggAccessCollection[]|false
	 */
	public function getCollectionsByMember($member_guid) {

		$callback = [$this, 'rowToElggAccessCollection'];

		$query = "
			SELECT ac.* FROM {$this->table} ac
				JOIN {$this->membership_table} acm
					ON ac.id = acm.access_collection_id
				WHERE acm.user_guid = :member_guid
				ORDER BY name ASC
		";

		return $this->db->getData($query, $callback, [
			':member_guid' => (int) $member_guid,
		]);
	}

	/**
	 * Return the name of an ACCESS_* constant or an access collection,
	 * but only if the logged in user owns the access collection or is an admin.
	 * Ownership requirement prevents us from exposing names of access collections
	 * that current user has been added to by other members and may contain
	 * sensitive classification of the current user (e.g. close friends vs acquaintances).
	 *
	 * Returns a string in the language of the user for global access levels, e.g.'Public, 'Friends', 'Logged in', 'Private';
	 * or a name of the owned access collection, e.g. 'My work colleagues';
	 * or a name of the group or other access collection, e.g. 'Group: Elgg technical support';
	 * or 'Limited' if the user access is restricted to read-only, e.g. a friends collection the user was added to
	 *
	 * @param int $entity_access_id The entity's access id
	 *
	 * @return string
	 * @since 1.11
	 */
	public function getReadableAccessLevel($entity_access_id) {
		$access = (int) $entity_access_id;

		$translator = $this->translator;

		// Check if entity access id is a defined global constant
		$access_array = [
			ACCESS_PRIVATE => $translator->translate('access:label:private'),
			ACCESS_FRIENDS => $translator->translate('access:label:friends'),
			ACCESS_LOGGED_IN => $translator->translate('access:label:logged_in'),
			ACCESS_PUBLIC => $translator->translate('access:label:public'),
		];

		if (array_key_exists($access, $access_array)) {
			return $access_array[$access];
		}

		// Entity access id is probably a custom access collection
		// Check if the user has write access to it and can see it's label
		// Admins should always be able to see the readable version
		$collection = $this->get($access);

		$user_guid = $this->session->getLoggedInUserGuid();
		
		if (!$collection || !$collection->canEdit()) {
			// return 'Limited' if the collection can not be loaded or it can not be edited
			return $translator->translate('access:limited:label');
		}

		return $collection->getDisplayName();
	}

}
