<?php

namespace Elgg\Database;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Elgg\Cache\AccessCache;
use Elgg\Config;
use Elgg\Database;
use Elgg\EventsService;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\Database\UserFetchFailureException;
use Elgg\I18n\Translator;
use Elgg\SessionManagerService;
use Elgg\Traits\Loggable;
use Elgg\UserCapabilities;

/**
 * Access collections database service
 *
 * @internal
 * @since 1.10.0
 */
class AccessCollections {

	use Loggable;

	/**
	 * @var string name of the access collections database table
	 */
	public const TABLE_NAME = 'access_collections';
	
	/**
	 * @var string name of the access collection membership database table
	 */
	public const MEMBERSHIP_TABLE_NAME = 'access_collection_membership';

	protected bool $init_complete = false;

	/**
	 * Constructor
	 *
	 * @param Config                $config          Config
	 * @param Database              $db              Database
	 * @param EntityTable           $entities        Entity table
	 * @param UserCapabilities      $capabilities    User capabilities
	 * @param AccessCache           $access_cache    Access cache
	 * @param EventsService         $events          Events
	 * @param SessionManagerService $session_manager Session
	 * @param Translator            $translator      Translator
	 */
	public function __construct(
		protected Config $config,
		protected Database $db,
		protected EntityTable $entities,
		protected UserCapabilities $capabilities,
		protected AccessCache $access_cache,
		protected EventsService $events,
		protected SessionManagerService $session_manager,
		protected Translator $translator) {
	}

	/**
	 * Mark the access system as initialized
	 *
	 * @return void
	 */
	public function markInitComplete(): void {
		$this->init_complete = true;
	}

	/**
	 * Returns an array of access IDs a user is permitted to see.
	 *
	 * Can be overridden with the 'access:collections:read', 'user' event.
	 * @warning A callback for that event needs to either not retrieve data
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
	 * @see elgg_get_write_access_array() for the access levels that a user can write to.
	 *
	 * @param int  $user_guid User ID; defaults to currently logged in user
	 * @param bool $flush     If set to true, will refresh the access ids from the
	 *                        database rather than using this function's cache.
	 *
	 * @return array An array of access collections ids
	 */
	public function getAccessArray(int $user_guid = 0, bool $flush = false): array {
		$cache = $this->access_cache;

		if ($flush) {
			$cache->clear();
		}

		if ($user_guid === 0) {
			$user_guid = $this->session_manager->getLoggedInUserGuid();
		}

		$hash = $user_guid . 'get_access_array';
		
		$access_array = $cache->load($hash);
		if ($access_array === null) {
			// Public access is always visible
			$access_array = [ACCESS_PUBLIC];

			// The following can only return sensible data for a known user.
			if ($user_guid) {
				$access_array[] = ACCESS_LOGGED_IN;

				// Get ACLs that user owns or is a member of
				$select = Select::fromTable(self::TABLE_NAME);

				$membership_query = $select->subquery(self::MEMBERSHIP_TABLE_NAME);
				$membership_query->select('access_collection_id')
					->where($select->compare('user_guid', '=', $user_guid, ELGG_VALUE_GUID));

				$select->select('id')
					->where($select->compare('owner_guid', '=', $user_guid, ELGG_VALUE_GUID))
					->orWhere($select->compare('id', 'in', $membership_query->getSQL()));

				$collections = $this->db->getData($select);
				if (!empty($collections)) {
					foreach ($collections as $collection) {
						$access_array[] = (int) $collection->id;
					}
				}

				$ignore_access = $this->capabilities->canBypassPermissionsCheck($user_guid);

				if ($ignore_access === true) {
					$access_array[] = ACCESS_PRIVATE;
				}
			}

			if ($this->init_complete) {
				$cache->save($hash, $access_array);
			}
		}

		$options = [
			'user_id' => $user_guid,
		];

		// see the warning in the docs for this function about infinite loop potential
		return $this->events->triggerResults('access:collections:read', 'user', $options, $access_array);
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
	 * @param \ElggEntity $entity    The entity to check access for
	 * @param int         $user_guid Optionally user_guid to check access for. Defaults to logged in user
	 *
	 * @return bool
	 */
	public function hasAccessToEntity(\ElggEntity $entity, int $user_guid = 0): bool {
		if ($entity->access_id === ACCESS_PUBLIC) {
			// Public entities are always accessible
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
			$user_guid = $user ? $user->guid : 0; // No GUID given and not logged in
		} catch (UserFetchFailureException $e) {
			// Not a user GUID
			$user_guid = 0;
		}

		if ($user_guid === $entity->owner_guid) {
			// Owners have access to their own content
			return true;
		}

		if (!empty($user_guid) && $entity->access_id === ACCESS_LOGGED_IN) {
			// Existing users have access to entities with logged in access
			return true;
		}

		// See #7159. Must not allow ignore access to affect query
		$row = elgg_call(ELGG_ENFORCE_ACCESS, function() use ($entity, $user_guid) {
			return $this->entities->getRow($entity->guid, $user_guid);
		});
		
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
	public function getWriteAccessArray(int $user_guid = 0, bool $flush = false, array $input_params = []): array {
		$cache = $this->access_cache;

		if ($flush) {
			$cache->clear();
		}

		if ($user_guid === 0) {
			$user_guid = $this->session_manager->getLoggedInUserGuid();
		}

		$hash = $user_guid . 'get_write_access_array';
		
		$access_array = $cache->load($hash);
		if ($access_array === null) {
			$access_array = [
				ACCESS_PRIVATE => $this->getReadableAccessLevel(ACCESS_PRIVATE),
				ACCESS_LOGGED_IN => $this->getReadableAccessLevel(ACCESS_LOGGED_IN),
				ACCESS_PUBLIC => $this->getReadableAccessLevel(ACCESS_PUBLIC),
			];

			$access_array += $this->getCollectionsForWriteAccess($user_guid);
			
			if ($this->init_complete) {
				$cache->save($hash, $access_array);
			}
		}

		$options = [
			'user_id' => $user_guid,
			'input_params' => $input_params,
		];
		
		$access_array = $this->events->triggerResults('access:collections:write', 'user', $options, $access_array);
		
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
	 * Returns an array of access collections to be used in the write access array
	 *
	 * @param int $owner_guid owner of the collections
	 *
	 * @return array
	 * @since 3.2
	 */
	protected function getCollectionsForWriteAccess(int $owner_guid): array {
		$subtypes = $this->events->triggerResults('access:collections:write:subtypes', 'user', ['owner_guid' => $owner_guid], []);
		
		$select = Select::fromTable(self::TABLE_NAME);
		
		$ors = [
			$select->compare('subtype', 'is null'),
		];
		if (!empty($subtypes)) {
			$ors[] = $select->compare('subtype', 'in', $subtypes, ELGG_VALUE_STRING);
		}
		
		$select->select('*')
			->where($select->compare('owner_guid', '=', $owner_guid, ELGG_VALUE_GUID))
			->andWhere($select->merge($ors, 'OR'))
			->orderBy('name', 'ASC');
		
		$collections = $this->db->getData($select, [$this, 'rowToElggAccessCollection']);
		if (empty($collections)) {
			return [];
		}
		
		$result = [];
		foreach ($collections as $collection) {
			$result[$collection->id] = $collection->getDisplayName();
		}
		
		return $result;
	}

	/**
	 * Can the user change this access collection?
	 *
	 * Use the event of 'access:collections:write', 'user' to change this.
	 * @see elgg_get_write_access_array() for details on the event.
	 *
	 * Respects access control disabling for admin users and {@link elgg_call()}
	 *
	 * @param int $collection_id The collection id
	 * @param int $user_guid     The user GUID to check for. Defaults to logged in user.
	 *
	 * @return bool
	 */
	public function canEdit(int $collection_id, int $user_guid = null): bool {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		if (!$user instanceof \ElggUser) {
			return false;
		}
		
		$collection = $this->get($collection_id);
		if (!$collection instanceof \ElggAccessCollection) {
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
	 * Triggers event sequence 'create', 'access_collection'
	 *
	 * @internal Access collections are stored in the access_collections table.
	 * Memberships to collections are in access_collections_membership.
	 *
	 * @param \ElggAccessCollection $acl the access collection to create
	 *
	 * @return bool
	 */
	public function create(\ElggAccessCollection $acl): bool {
		if (!empty($acl->id)) {
			return $this->update($acl);
		}
		
		return $this->events->triggerSequence('create', $acl->getType(), $acl, function (\ElggAccessCollection $acl) {
			$insert = Insert::intoTable(self::TABLE_NAME);
			$insert->values([
				'name' => $insert->param($acl->name, ELGG_VALUE_STRING),
				'subtype' => $insert->param($acl->subtype, ELGG_VALUE_STRING),
				'owner_guid' => $insert->param($acl->owner_guid, ELGG_VALUE_GUID),
			]);
			
			$id = $this->db->insertData($insert);
			if (empty($id)) {
				return false;
			}
			
			$acl->id = $id;
			
			$this->access_cache->clear();
			
			return true;
		});
	}

	/**
	 * Update an existing access collection
	 *
	 * @param \ElggAccessCollection $acl the access collection to update
	 *
	 * @return bool
	 */
	public function update(\ElggAccessCollection $acl): bool {
		if (empty($acl->id)) {
			return $this->create($acl);
		}
		
		return $this->events->triggerSequence('update', $acl->getType(), $acl, function (\ElggAccessCollection $acl) {
			$update = Update::table(self::TABLE_NAME);
			$update->set('name', $update->param($acl->name, ELGG_VALUE_STRING))
				->set('subtype', $update->param($acl->subtype, ELGG_VALUE_STRING))
				->set('owner_guid', $update->param($acl->owner_guid, ELGG_VALUE_GUID))
				->where($update->compare('id', '=', $acl->id, ELGG_VALUE_ID));
			
			if (!$this->db->updateData($update)) {
				return false;
			}
			
			$this->access_cache->clear();
			
			return true;
		});
	}

	/**
	 * Deletes a collection and its membership information
	 *
	 * @param \ElggAccessCollection $acl the access collection to update
	 *
	 * @return bool
	 */
	public function delete(\ElggAccessCollection $acl): bool {
		if (empty($acl->id)) {
			return false;
		}
		
		return $this->events->triggerSequence('delete', $acl->getType(), $acl, function (\ElggAccessCollection $acl) {
			$delete = Delete::fromTable(self::TABLE_NAME);
			$delete->where($delete->compare('id', '=', $acl->id, ELGG_VALUE_ID));
			
			if (!$this->db->deleteData($delete)) {
				return false;
			}
			
			// cleanup access collection membership (doesn't affect the result of deleting the ACL)
			$delete_membership = Delete::fromTable(self::MEMBERSHIP_TABLE_NAME);
			$delete_membership->where($delete_membership->compare('access_collection_id', '=', $acl->id, ELGG_VALUE_ID));
			
			$this->db->deleteData($delete_membership);
			
			// clear cache
			$this->access_cache->clear();
			
			return true;
		});
	}

	/**
	 * Transforms a database row to an instance of ElggAccessCollection
	 *
	 * @param \stdClass $row Database row
	 *
	 * @return \ElggAccessCollection
	 */
	public function rowToElggAccessCollection(\stdClass $row): \ElggAccessCollection {
		return new \ElggAccessCollection($row);
	}

	/**
	 * Get a specified access collection
	 *
	 * @param int $collection_id The collection ID
	 *
	 * @return \ElggAccessCollection|null
	 */
	public function get(int $collection_id): ?\ElggAccessCollection {
		$query = Select::fromTable(self::TABLE_NAME);
		$query->select('*')
			->where($query->compare('id', '=', $collection_id, ELGG_VALUE_ID));
		
		return $this->db->getDataRow($query, [$this, 'rowToElggAccessCollection']) ?: null;
	}

	/**
	 * Check if user is already in the collection
	 *
	 * @param int $user_guid     GUID of the user
	 * @param int $collection_id ID of the collection
	 *
	 * @return bool
	 */
	public function hasUser(int $user_guid, int $collection_id): bool {
		$options = [
			'guids' => $user_guid,
			'count' => true,
		];
		return (bool) $this->getMembers($collection_id, $options);
	}

	/**
	 * Adds a user to an access collection.
	 *
	 * Triggers the 'access:collections:add_user', 'collection' event.
	 *
	 * @param int $user_guid     GUID of the user to add
	 * @param int $collection_id ID of the collection to add them to
	 *
	 * @return bool
	 */
	public function addUser(int $user_guid, int $collection_id): bool {
		$collection = $this->get($collection_id);
		if (!$collection instanceof \ElggAccessCollection) {
			return false;
		}
		
		$user = $this->entities->get($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}

		$event_params = [
			'collection_id' => $collection->id,
			'user_guid' => $user_guid
		];

		// @todo https://github.com/Elgg/Elgg/issues/10823
		$result = $this->events->triggerResults('access:collections:add_user', 'collection', $event_params, true);
		if ($result === false) {
			return false;
		}

		// if someone tries to insert the same data twice, we catch the exception and return true
		$insert = Insert::intoTable(self::MEMBERSHIP_TABLE_NAME);
		$insert->values([
			'access_collection_id' => $insert->param($collection_id, ELGG_VALUE_ID),
			'user_guid' => $insert->param($user_guid, ELGG_VALUE_GUID),
		]);
		
		try {
			$result = $this->db->insertData($insert);
		} catch (DatabaseException $e) {
			$prev = $e->getPrevious();
			if ($prev instanceof UniqueConstraintViolationException) {
				// duplicate key exception, catched for performance reasons
				return true;
			}
			
			throw $e;
		}

		$this->access_cache->clear();

		return $result !== false;
	}

	/**
	 * Removes a user from an access collection.
	 *
	 * Triggers the 'access:collections:remove_user', 'collection' event.
	 *
	 * @param int $user_guid     GUID of the user
	 * @param int $collection_id ID of the collection
	 *
	 * @return bool
	 */
	public function removeUser(int $user_guid, int $collection_id): bool {

		$params = [
			'collection_id' => $collection_id,
			'user_guid' => $user_guid,
		];

		// @todo https://github.com/Elgg/Elgg/issues/10823
		if (!$this->events->triggerResults('access:collections:remove_user', 'collection', $params, true)) {
			return false;
		}

		$delete = Delete::fromTable(self::MEMBERSHIP_TABLE_NAME);
		$delete->where($delete->compare('access_collection_id', '=', $collection_id, ELGG_VALUE_ID))
			->andWhere($delete->compare('user_guid', '=', $user_guid, ELGG_VALUE_GUID));
		
		$this->access_cache->clear();

		return (bool) $this->db->deleteData($delete);
	}

	/**
	 * Returns access collections
	 *
	 * @param array $options Options to get access collections by
	 *                       Supported are 'owner_guid', 'subtype'
	 *
	 * @return \ElggAccessCollection[]
	 */
	public function getEntityCollections(array $options = []): array {
		$supported_options = ['owner_guid', 'subtype'];

		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->orderBy('name', 'ASC');

		foreach ($supported_options as $option) {
			$option_value = elgg_extract($option, $options);
			if (!isset($option_value)) {
				continue;
			}

			switch ($option) {
				case 'owner_guid':
					$select->andWhere($select->compare($option, '=', $option_value, ELGG_VALUE_GUID));
					break;
				case 'subtype':
					$select->andWhere($select->compare($option, '=', $option_value, ELGG_VALUE_STRING));
					break;
			}
		}

		return $this->db->getData($select, [$this, 'rowToElggAccessCollection']);
	}

	/**
	 * Get members of an access collection
	 *
	 * @param int   $collection_id The collection's ID
	 * @param array $options       Ege* options
	 *
	 * @return \ElggData[]|int|mixed
	 */
	public function getMembers(int $collection_id, array $options = []) {
		$options['wheres'][] = function(QueryBuilder $qb, $table_alias) use ($collection_id) {
			$qb->join($table_alias, self::MEMBERSHIP_TABLE_NAME, 'acm', $qb->compare('acm.user_guid', '=', "{$table_alias}.guid"));

			return $qb->compare('acm.access_collection_id', '=', $collection_id, ELGG_VALUE_INTEGER);
		};

		return Entities::find($options);
	}

	/**
	 * Return an array of collections that the entity is member of
	 *
	 * @param int $member_guid GUID of th member
	 *
	 * @return \ElggAccessCollection[]
	 */
	public function getCollectionsByMember(int $member_guid): array {
		$select = Select::fromTable(self::TABLE_NAME, 'ac');
		$select->join($select->getTableAlias(), self::MEMBERSHIP_TABLE_NAME, 'acm', $select->compare("{$select->getTableAlias()}.id", '=', 'acm.access_collection_id'));

		$select->select("{$select->getTableAlias()}.*")
			->where($select->compare('acm.user_guid', '=', $member_guid, ELGG_VALUE_GUID))
			->orderBy('name', 'ASC');

		return $this->db->getData($select, [$this, 'rowToElggAccessCollection']);
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
	public function getReadableAccessLevel(int $entity_access_id): string {
		$translator = $this->translator;

		// Check if entity access id is a defined global constant
		$access_array = [
			ACCESS_PRIVATE => $translator->translate('access:label:private'),
			ACCESS_FRIENDS => $translator->translate('access:label:friends'),
			ACCESS_LOGGED_IN => $translator->translate('access:label:logged_in'),
			ACCESS_PUBLIC => $translator->translate('access:label:public'),
		];

		if (array_key_exists($entity_access_id, $access_array)) {
			return $access_array[$entity_access_id];
		}

		// Entity access id is probably a custom access collection
		// Check if the user has write access to it and can see it's label
		// Admins should always be able to see the readable version
		$collection = $this->get($entity_access_id);

		if (!$collection instanceof \ElggAccessCollection || !$collection->canEdit()) {
			// return 'Limited' if the collection can not be loaded or it can not be edited
			return $translator->translate('access:limited:label');
		}

		return $collection->getDisplayName();
	}
}
