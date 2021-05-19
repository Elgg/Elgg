<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Delete;
use Elgg\Database\EntityTable as DbEntityTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;

/**
 * This mock table is designed to simplify testing of DB-dependent services.
 * It populates the mock database with query specifications for predictable results
 * when entities are requested, updated or deleted.
 *
 * Note that this mock is not designed for testing the entity table itself.
 * When testing the entity table, you should define query specs individually for the
 * method being tested.
 */
class EntityTable extends DbEntityTable {

	/**
	 * @var \stdClass[]
	 */
	public $rows = [];

	/**
	 * @var int
	 */
	static $iterator = 100;

	/**
	 * @var array
	 */
	private $query_specs = [];

	/**
	 * {@inheritdoc}
	 */
	public function getRow($guid, $user_guid = null) {
		if ($guid === 1) {
			return (object) [
				'guid' => 1,
				'type' => 'site',
				'subtype' => 'site',
				'owner_guid' => 0,
				'container_guid' => 0,
				'access_id' => ACCESS_PUBLIC,
				'time_created' => time(),
				'time_updated' => time(),
				'last_action' => time(),
				'enabled' => 'yes',
			];
		}

		if (empty($this->rows[$guid])) {
			return false;
		}

		$entity = $this->rowToElggStar($this->rows[$guid]);

		if ($entity->access_id == ACCESS_PUBLIC) {
			// Public entities are always accessible
			return $entity;
		}

		$user_guid = isset($user_guid) ? (int) $user_guid : elgg_get_logged_in_user_guid();

		if (_elgg_services()->userCapabilities->canBypassPermissionsCheck($user_guid)) {
			return $entity;
		}

		if ($user_guid && $user_guid == $entity->owner_guid) {
			// Owners have access to their own content
			return $entity;
		}

		if ($user_guid && $entity->access_id == ACCESS_LOGGED_IN) {
			// Existing users have access to entities with logged in access
			return $entity;
		}

		return parent::getRow($guid, $user_guid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function insertRow(\stdClass $row, array $attributes = []) {
		$subtype = isset($row->subtype) ? $row->subtype : null;
		$this->setup(null, $row->type, $subtype, array_merge($attributes, (array) $row));

		return parent::insertRow($row);
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateRow($guid, \stdClass $row) {
		$attributes = array_merge((array) $this->rows[$guid], (array) $row);

		// Rebuild query specs for the udpated row
		$this->addQuerySpecs((object) $attributes);

		return parent::updateRow($guid, $row);
	}

	/**
	 * Setup a mock entity
	 *
	 * @param int    $guid       GUID of the mock entity
	 * @param string $type       Type of the mock entity
	 * @param string $subtype    Subtype of the mock entity
	 * @param array  $attributes Attributes of the mock entity
	 *
	 * @return \ElggEntity
	 */
	public function setup($guid, $type, $subtype, array $attributes = []) {
		while (!isset($guid)) {
			if ($type === 'site') {
				$guid = 1;
			} else {
				static::$iterator++;
				if (!isset($this->row[static::$iterator])) {
					$guid = static::$iterator;
				}
			}
		}

		$attributes['guid'] = $guid;
		$attributes['type'] = $type;
		$attributes['subtype'] = $subtype;

		$time = $this->getCurrentTime()->getTimestamp();

		$primary_attributes = [
			'guid' => $guid,
			'type' => $type,
			'subtype' => $subtype,
			'owner_guid' => 0,
			'container_guid' => 0,
			'access_id' => ACCESS_PUBLIC,
			'time_created' => $time,
			'time_updated' => $time,
			'last_action' => $time,
			'enabled' => 'yes',
		];

		$map = array_merge($primary_attributes, $attributes);

		// get filled in primary attributes
		$primary_attributes = (object) array_intersect_key($map, $primary_attributes);

		$this->rows[$guid] = $primary_attributes;
		$this->addQuerySpecs($primary_attributes);

		$entity = $this->rowToElggStar($primary_attributes);
		if (!($entity instanceof \ElggEntity)) {
			_elgg_services()->logger->error("Failed creating a mock entity with attributes " . var_export($primary_attributes, true));
		}

		$attrs = (object) $map;
		foreach ($attrs as $name => $value) {
			if (isset($entity->$name) && $entity->$name == $value) {
				continue;
			}
			
			switch ($name) {
				case 'subtype':
				case 'password_hash':
					break;
				case 'admin':
					if ($entity instanceof \ElggUser && $value === 'yes') {
						$entity->makeAdmin();
					}
					break;
				case 'banned':
					if ($entity instanceof \ElggUser && $value === 'yes') {
						$entity->ban();
					}
					break;
				default:
					// not an attribute, so needs to be set again
					$entity->$name = $value;
					break;
			}
		}

		//$entity->cache();

		return $entity;
	}

	/**
	 * Iterate ID
	 * @return int
	 */
	public function iterate() {
		static::$iterator++;

		return static::$iterator;
	}

	/**
	 * Clear query specs
	 *
	 * @param int $guid GUID
	 *
	 * @return void
	 */
	public function clearQuerySpecs($guid) {
		if (!empty($this->query_specs[$guid])) {
			foreach ($this->query_specs[$guid] as $spec) {
				$this->db->removeQuerySpec($spec);
			}
		}
	}

	/**
	 * Add query specs
	 *
	 * @param \stdClass $row Entity table row
	 *
	 * @return void
	 */
	public function addQuerySpecs(\stdClass $row) {

		// Clear previous added specs, if any
		$this->clearQuerySpecs($row->guid);

		// We may have been too paranoid about access
		// If there is a need for more robust access controls in unit tests
		// uncomment the following line and remove getRow method
		//$this->addSelectQuerySpecs($row);

		$this->addInsertQuerySpecs($row);
		$this->addUpdateQuerySpecs($row);
		$this->addDeleteQuerySpecs($row);
	}

	/**
	 * Add query specs for SELECT queries
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addSelectQuerySpecs(\stdClass $row) {

		// Access SQL for this row might differ based on:
		//  - logged in user
		//  - show hidden entities status
		//  - ignored access
		//
		// To simplify querying, we will populate specs for all combinations of the above
		// Given that tests log in and log out various users, and test
		// entities against owner/container, we will populate queries for
		// a set of users, and then validate their access to entity
		// whenever a specific query is run
		$access_user_guids = array_unique([
			0,
			(int) $row->guid,
			(int) $row->owner_guid,
			(int) $row->container_guid,
			(int) elgg_get_logged_in_user_guid(),
		]);

		$access_combinations = [];

		foreach ($access_user_guids as $access_user_guid) {
			$access_combinations[] = [
				'user_guid' => $access_user_guid,
				'ignore_access' => false,
				'use_enabled_clause' => true,
			];
			$access_combinations[] = [
				'user_guid' => $access_user_guid,
				'ignore_access' => true,
				'use_enabled_clause' => true,
			];
			$access_combinations[] = [
				'user_guid' => $access_user_guid,
				'ignore_access' => false,
				'use_enabled_clause' => false,
			];
			$access_combinations[] = [
				'user_guid' => $access_user_guid,
				'ignore_access' => true,
				'use_enabled_clause' => false,
			];
		}

		foreach ($access_combinations as $access_combination) {

			$where = new EntityWhereClause();
			$where->ignore_access = $access_combination['ignore_access'];
			$where->use_enabled_clause = $access_combination['use_enabled_clause'];
			$where->viewer_guid = $access_combination['user_guid'];
			$where->guids = $row->guid;

			$select = Select::fromTable('entities', 'e');
			$select->select('e.*');
			$select->addClause($where);

			$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
				'sql' => $select->getSQL(),
				'params' => $select->getParameters(),
				'results' => function () use ($row, $access_combination) {
					if (!isset($this->rows[$row->guid])) {
						return [];
					}
					$row = $this->rows[$row->guid];

					if ($access_combination['use_enabled_clause'] && $row->enabled != 'yes') {
						// The SELECT query would contain ('enabled' = 'yes')
						return [];
					}

					$has_access = $this->validateRowAccess($row);

					return $has_access ? [$row] : [];
				}
			]);
		}
	}

	/**
	 * Check if the user logged in when the query is run, has access to a given data row
	 * This is a reverse engineered approach to an SQL query generated by AccessCollections::getWhereSql()
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return bool
	 */
	public function validateRowAccess($row) {

		if (elgg_get_ignore_access()) {
			return true;
		}

		if ($row->access_id == ACCESS_PUBLIC) {
			return true;
		}

		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return false;
		}

		if ($row->access_id == ACCESS_LOGGED_IN && elgg_is_logged_in()) {
			return true;
		}

		if ($user->isAdmin()) {
			return true;
		}

		if ($row->owner_guid == $user->guid) {
			return true;
		}

		if ($row->access_id == ACCESS_PRIVATE && $row->owner_guid == $user->guid) {
			return true;
		}

		$access_array = _elgg_services()->accessCollections->getAccessArray($user->guid);
		return in_array($row->access_id, $access_array);;
	}

	/**
	 * Query specs for INSERT operations
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addInsertQuerySpecs(\stdClass $row) {
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'type' => $insert->param($row->type, ELGG_VALUE_STRING),
			'subtype' => $insert->param($row->subtype, ELGG_VALUE_STRING),
			'owner_guid' => $insert->param($row->owner_guid, ELGG_VALUE_GUID),
			'container_guid' => $insert->param($row->container_guid, ELGG_VALUE_GUID),
			'access_id' => $insert->param($row->access_id, ELGG_VALUE_ID),
			'time_created' => $insert->param($row->time_created, ELGG_VALUE_TIMESTAMP),
			'time_updated' => $insert->param($row->time_updated, ELGG_VALUE_TIMESTAMP),
			'last_action' => $insert->param($row->last_action, ELGG_VALUE_TIMESTAMP),
		]);
		
		$this->query_specs[$row->guid][] = _elgg_services()->db->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->guid,
		]);
	}

	/**
	 * Query specs for UPDATE operations
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addUpdateQuerySpecs(\stdClass $row) {
		$update = Update::table(self::TABLE_NAME);
		$update->set('owner_guid', $update->param($row->owner_guid, ELGG_VALUE_GUID))
			->set('container_guid', $update->param($row->container_guid, ELGG_VALUE_GUID))
			->set('access_id', $update->param($row->access_id, ELGG_VALUE_ID))
			->set('time_created', $update->param($row->time_created, ELGG_VALUE_TIMESTAMP))
			->set('time_updated', $update->param($row->time_updated, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $row->guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $update->getSQL(),
			'params' => $update->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->guid])) {
					$this->rows[$row->guid] = $row;

					return [$row->guid];
				}

				return [];
			},
		]);

		// Disable
		$qb = Update::table(self::TABLE_NAME);
		$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
			->where($qb->compare('guid', '=', $row->guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->guid])) {
					$row->enabled = 'no';
					$this->rows[$row->guid] = $row;
					$this->addQuerySpecs($row);

					return [$row->guid];
				}

				return [];
			},
			'times' => 1,
		]);

		// Enable
		$qb = Update::table(self::TABLE_NAME);
		$qb->set('enabled', $qb->param('yes', ELGG_VALUE_STRING))
			->where($qb->compare('guid', '=', $row->guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->guid])) {
					$row->enabled = 'yes';
					$this->rows[$row->guid] = $row;
					$this->addQuerySpecs($row);

					return [$row->guid];
				}

				return [];
			},
			'times' => 1,
		]);

		// Update last action
		$time = $this->getCurrentTime()->getTimestamp();
		
		$update = Update::table(self::TABLE_NAME);
		$update->set('last_action', $update->param($time, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $row->guid, ELGG_VALUE_GUID));
		
		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $update->getSQL(),
			'params' => $update->getParameters(),
			'results' => function () use ($row, $time) {
				if (isset($this->rows[$row->guid])) {
					$row->last_action = $time;
					$this->rows[$row->guid] = $row;
					$this->addQuerySpecs($row);

					return [$row->guid];
				}

				return [];
			},
		]);
	}

	/**
	 * Query specs for DELETE operations
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addDeleteQuerySpecs(\stdClass $row) {

		$qb = Delete::fromTable('entities');
		$qb->where($qb->compare('guid', '=', $row->guid, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			// We are using results instead of 'row_count' to give an accurate
			// count of deleted rows
			'results' => function () use ($row) {
				if (isset($this->rows[$row->guid])) {
					// Query spec will be cleared after row is deleted from objects table
					unset($this->rows[$row->guid]);

					return [$row->guid];
				}

				return [];
			},
			'times' => 1,
		]);

		// Entity might not have any relationships, therefore adding the spec here
		// and not in the relationships table mock
		// @todo: figure out a way to remove this from relationships table
		foreach (['guid_one', 'guid_two'] as $column) {
			$delete = Delete::fromTable('entity_relationships');
			$delete->where($delete->compare($column, '=', $row->guid, ELGG_VALUE_GUID));
			
			$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
				'sql' => $delete->getSQL(),
				'params' => $delete->getParameters(),
				'row_count' => 0,
				'times' => 1,
			]);
		}

		// Private settings cleanup
		$qb = Delete::fromTable('private_settings');
		$qb->where($qb->compare('entity_guid', '=', $row->guid, ELGG_VALUE_INTEGER));
		
		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'row_count' => 0,
			'times' => 1,
		]);
	}
}
