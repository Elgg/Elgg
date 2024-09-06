<?php

namespace Elgg\Database;

use Elgg\Cache\AccessCache;
use Elgg\Cache\MetadataCache;
use Elgg\Database;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\EventsService as Events;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\LogicException;
use Elgg\Traits\TimeUsing;

/**
 * This class interfaces with the database to perform CRUD operations on metadata
 *
 * @internal
 */
class MetadataTable {

	use TimeUsing;
	
	protected const MYSQL_TEXT_BYTE_LIMIT = 65535;
	
	public const TABLE_NAME = 'metadata';
	
	public const DEFAULT_JOIN_ALIAS = 'n_table';
	
	/**
	 * Constructor
	 *
	 * @param AccessCache   $access_cache   The access cache
	 * @param MetadataCache $metadata_cache A cache for this table
	 * @param Database      $db             The Elgg database
	 * @param Events        $events         The events registry
	 * @param EntityTable   $entityTable    The EntityTable database wrapper
	 */
	public function __construct(
		protected AccessCache $access_cache,
		protected MetadataCache $metadata_cache,
		protected Database $db,
		protected Events $events,
		protected EntityTable $entityTable
	) {
	}

	/**
	 * Get popular tags and their frequencies
	 *
	 * Accepts all options supported by {@see elgg_get_metadata()}
	 *
	 * Returns an array of objects that include "tag" and "total" properties
	 *
	 * @param array $options Options
	 *
	 * @option int      $threshold Minimum number of tag occurrences
	 * @option string[] $tag_names tag names to include in search
	 *
	 * @return \stdClass[]|false
	 */
	public function getTags(array $options = []) {
		$defaults = [
			'threshold' => 1,
			'tag_names' => [],
		];

		$options = array_merge($defaults, $options);

		$singulars = ['tag_name'];
		$options = QueryOptions::normalizePluralOptions($options, $singulars);

		$tag_names = elgg_extract('tag_names', $options, ['tags'], false);

		$threshold = elgg_extract('threshold', $options, 1, false);

		unset($options['tag_names']);
		unset($options['threshold']);
		
		// custom selects
		$options['selects'] = [
			function(QueryBuilder $qb, $main_alias) {
				return "{$main_alias}.value AS tag";
			},
			function(QueryBuilder $qb, $main_alias) {
				return "COUNT({$main_alias}.id) AS total";
			},
		];
		
		// additional wheres
		$wheres = (array) elgg_extract('wheres', $options, []);
		$wheres[] = function(QueryBuilder $qb, $main_alias) use ($tag_names) {
			return $qb->compare("{$main_alias}.name", 'in', $tag_names, ELGG_VALUE_STRING);
		};
		$wheres[] = function(QueryBuilder $qb, $main_alias) {
			return $qb->compare("{$main_alias}.value", '!=', '', ELGG_VALUE_STRING);
		};
		$options['wheres'] = $wheres;
		
		// custom group by
		$options['group_by'] = [
			function(QueryBuilder $qb, $main_alias) {
				return "{$main_alias}.value";
			},
		];
		
		// having
		$having = (array) elgg_extract('having', $options, []);
		$having[] = function(QueryBuilder $qb, $main_alias) use ($threshold) {
			return $qb->compare('total', '>=', $threshold, ELGG_VALUE_INTEGER);
		};
		$options['having'] = $having;
		
		// order by
		$options['order_by'] = [
			new OrderByClause('total', 'desc'),
		];
		
		// custom callback
		$options['callback'] = function($row) {
			$result = new \stdClass();
			$result->tag = $row->tag;
			$result->total = (int) $row->total;
			
			return $result;
		};
		
		return $this->getAll($options);
	}

	/**
	 * Get a specific metadata object by its id
	 *
	 * @see MetadataTable::getAll()
	 *
	 * @param int $id The id of the metadata object being retrieved.
	 *
	 * @return \ElggMetadata|null
	 */
	public function get(int $id): ?\ElggMetadata {
		$qb = Select::fromTable(self::TABLE_NAME);
		$qb->select('*');

		$where = new MetadataWhereClause();
		$where->ids = $id;
		$qb->addClause($where);

		$row = $this->db->getDataRow($qb);
		return $row ? new \ElggMetadata($row) : null;
	}

	/**
	 * Deletes metadata using its ID
	 *
	 * @param \ElggMetadata $metadata Metadata
	 *
	 * @return bool
	 */
	public function delete(\ElggMetadata $metadata): bool {
		if (!$metadata->id) {
			return false;
		}

		if (!$this->events->trigger('delete', 'metadata', $metadata)) {
			return false;
		}

		$qb = Delete::fromTable(self::TABLE_NAME);
		$qb->where($qb->compare('id', '=', $metadata->id, ELGG_VALUE_INTEGER));

		$deleted = $this->db->deleteData($qb);

		if ($deleted) {
			$this->metadata_cache->delete($metadata->entity_guid);
		}

		return $deleted !== false;
	}

	/**
	 * Create a new metadata object, or update an existing one (if multiple is allowed)
	 *
	 * Metadata can be an array by setting allow_multiple to true, but it is an
	 * indexed array with no control over the indexing
	 *
	 * @param \ElggMetadata $metadata       Metadata
	 * @param bool          $allow_multiple Allow multiple values for one key. Default is false
	 *
	 * @return int|false id of metadata or false if failure
	 * @throws LogicException
	 */
	public function create(\ElggMetadata $metadata, bool $allow_multiple = false): int|false {
		if (!isset($metadata->value) || !isset($metadata->entity_guid)) {
			elgg_log('Metadata must have a value and entity guid', \Psr\Log\LogLevel::ERROR);
			return false;
		}

		if (!$this->entityTable->exists($metadata->entity_guid)) {
			elgg_log("Can't create metadata on a non-existing entity_guid", \Psr\Log\LogLevel::ERROR);
			return false;
		}
		
		if (!is_scalar($metadata->value)) {
			elgg_log('To set multiple metadata values use ElggEntity::setMetadata', \Psr\Log\LogLevel::ERROR);
			return false;
		}

		if ($metadata->id) {
			if ($this->update($metadata)) {
				return $metadata->id;
			}
		}

		if (strlen($metadata->value) > self::MYSQL_TEXT_BYTE_LIMIT) {
			elgg_log("Metadata '{$metadata->name}' is above the MySQL TEXT size limit and may be truncated.", \Psr\Log\LogLevel::WARNING);
		}

		if (!$allow_multiple) {
			$id = $this->getIDsByName($metadata->entity_guid, $metadata->name);

			if (is_array($id)) {
				throw new LogicException("
					Multiple '{$metadata->name}' metadata values exist for entity [guid: {$metadata->entity_guid}].
					Use ElggEntity::setMetadata()
				");
			}

			if ($id > 0) {
				$metadata->id = $id;

				if ($this->update($metadata)) {
					return $metadata->id;
				}
			}
		}

		if (!$this->events->triggerBefore('create', 'metadata', $metadata)) {
			return false;
		}

		$time_created = $this->getCurrentTime()->getTimestamp();

		$qb = Insert::intoTable(self::TABLE_NAME);
		$qb->values([
			'name' => $qb->param($metadata->name, ELGG_VALUE_STRING),
			'entity_guid' => $qb->param($metadata->entity_guid, ELGG_VALUE_INTEGER),
			'value' => $qb->param($metadata->value, $metadata->value_type === 'text' ? ELGG_VALUE_STRING : ELGG_VALUE_INTEGER),
			'value_type' => $qb->param($metadata->value_type, ELGG_VALUE_STRING),
			'time_created' => $qb->param($time_created, ELGG_VALUE_INTEGER),
		]);

		$id = $this->db->insertData($qb);

		if ($id === 0) {
			return false;
		}

		$metadata->id = (int) $id;
		$metadata->time_created = $time_created;

		if (!$this->events->trigger('create', 'metadata', $metadata)) {
			$this->delete($metadata);
			
			return false;
		}
		
		$this->metadata_cache->delete($metadata->entity_guid);

		$this->events->triggerAfter('create', 'metadata', $metadata);

		return $id;
	}

	/**
	 * Update a specific piece of metadata
	 *
	 * @param \ElggMetadata $metadata Updated metadata
	 *
	 * @return bool
	 */
	public function update(\ElggMetadata $metadata): bool {
		if (!$this->entityTable->exists($metadata->entity_guid)) {
			elgg_log("Can't update metadata to a non-existing entity_guid", \Psr\Log\LogLevel::ERROR);
			return false;
		}
		
		if (!$this->events->triggerBefore('update', 'metadata', $metadata)) {
			return false;
		}

		if (strlen($metadata->value) > self::MYSQL_TEXT_BYTE_LIMIT) {
			elgg_log("Metadata '{$metadata->name}' is above the MySQL TEXT size limit and may be truncated.", \Psr\Log\LogLevel::WARNING);
		}

		$qb = Update::table(self::TABLE_NAME);
		$qb->set('name', $qb->param($metadata->name, ELGG_VALUE_STRING))
			->set('value', $qb->param($metadata->value, $metadata->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING))
			->set('value_type', $qb->param($metadata->value_type, ELGG_VALUE_STRING))
			->where($qb->compare('id', '=', $metadata->id, ELGG_VALUE_INTEGER));

		$result = $this->db->updateData($qb);

		if ($result === false) {
			return false;
		}

		$this->metadata_cache->delete($metadata->entity_guid);

		$this->events->trigger('update', 'metadata', $metadata);
		$this->events->triggerAfter('update', 'metadata', $metadata);

		return true;
	}

	/**
	 * Returns metadata
	 *
	 * Accepts all {@link elgg_get_entities()} options for entity restraints.
	 *
	 * @see     elgg_get_entities()
	 *
	 * @param array $options Options
	 *
	 * @return \ElggMetadata[]|mixed
	 */
	public function getAll(array $options = []) {
		$options['metastring_type'] = 'metadata';
		$options = QueryOptions::normalizeMetastringOptions($options);

		return Metadata::find($options);
	}

	/**
	 * Returns metadata rows
	 *
	 * Used internally for metadata preloading
	 *
	 * @param array $guids Array of guids to fetch metadata rows for
	 *
	 * @return \ElggMetadata[]
	 *
	 * @internal
	 */
	public function getRowsForGuids(array $guids): array {
		$qb = Select::fromTable(self::TABLE_NAME);
		$qb->select('*')
			->where($qb->compare('entity_guid', 'IN', $guids, ELGG_VALUE_GUID))
			->orderBy('entity_guid', 'asc')
			->addOrderBy('time_created', 'asc')
			->addOrderBy('id', 'asc');
		
		return $this->db->getData($qb, function ($row) {
			return new \ElggMetadata($row);
		});
	}

	/**
	 * Deletes metadata based on $options.
	 *
	 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
	 *          This requires at least one constraint:
	 *          metadata_name(s), metadata_value(s), or guid(s) must be set.
	 *
	 * @see     elgg_get_metadata()
	 * @see     elgg_get_entities()
	 *
	 * @param array $options Options
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function deleteAll(array $options): bool {
		$required = [
			'guid', 'guids',
			'metadata_name', 'metadata_names',
			'metadata_value', 'metadata_values',
		];

		$found = false;
		foreach ($required as $key) {
			// check that it exists and is something.
			if (isset($options[$key]) && !elgg_is_empty($options[$key])) {
				$found = true;
				break;
			}
		}

		if (!$found) {
			// requirements not met
			throw new InvalidArgumentException(__METHOD__ . ' requires at least one of the following keys in $options: ' . implode(', ', $required));
		}

		// This moved last in case an object's constructor sets metadata. Currently the batch
		// delete process has to create the entity to delete its metadata. See #5214
		if (empty($options['guid'])) {
			$this->access_cache->clear();
			$this->metadata_cache->clear();
		} else {
			$this->entityTable->invalidateCache($options['guid']);
		}

		$options['batch'] = true;
		$options['batch_size'] = 50;
		$options['batch_inc_offset'] = false;

		$metadata = Metadata::find($options);
		$count = $metadata->count();

		if (!$count) {
			return true;
		}

		$success = 0;
		/* @var $md \ElggMetadata */
		foreach ($metadata as $md) {
			if ($md->delete()) {
				$success++;
			}
		}

		return $success === $count;
	}

	/**
	 * Returns ID(s) of metadata with a particular name attached to an entity
	 *
	 * @param int    $entity_guid Entity guid
	 * @param string $name        Metadata name
	 *
	 * @return int[]|int|null
	 */
	protected function getIDsByName(int $entity_guid, string $name) {
		$cached_metadata = $this->metadata_cache->load($entity_guid);
		if ($cached_metadata !== null) {
			$ids = [];
			foreach ($cached_metadata as $md) {
				if ($md->name !== $name) {
					continue;
				}
				
				$ids[] = $md->id;
			}
		} else {
			$qb = Select::fromTable(self::TABLE_NAME);
			$qb->select('id')
				->where($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER))
				->andWhere($qb->compare('name', '=', $name, ELGG_VALUE_STRING));

			$callback = function (\stdClass $row) {
				return (int) $row->id;
			};

			$ids = $this->db->getData($qb, $callback);
		}

		if (empty($ids)) {
			return null;
		}

		if (is_array($ids) && count($ids) === 1) {
			return array_shift($ids);
		}

		return $ids;
	}
}
