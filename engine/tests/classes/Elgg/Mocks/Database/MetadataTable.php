<?php

namespace Elgg\Mocks\Database;

use Elgg\Cache\MetadataCache;
use Elgg\Database;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Delete;
use Elgg\Database\EntityTable;
use Elgg\Database\Insert;
use Elgg\Database\MetadataTable as DbMetadataTabe;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\EventsService as Events;
use ElggMetadata;

/**
 * @group ElggMetadata
 */
class MetadataTable extends DbMetadataTabe {

	/**
	 * @var \stdClass[]
	 */
	public $rows = [];

	/**
	 * DB query query_specs
	 * @var array
	 */
	public $query_specs = [];

	/**
	 * @var int
	 */
	static $iterator = 100;

	public function __construct(MetadataCache $metadata_cache, Database $db, Events $events, EntityTable $entityTable) {
		$this->setCurrentTime();
		parent::__construct($metadata_cache, $db, $events, $entityTable);
	}

	/**
	 * {@inheritdoc}
	 */
	public function create(ElggMetadata $metadata, $allow_multiple = false) {
		if (!isset($metadata->value) || !isset($metadata->entity_guid)) {
			elgg_log("Metadata must have a value and entity guid", 'ERROR');
			return false;
		}
		
		if (!$this->entityTable->exists($metadata->entity_guid)) {
			elgg_log("Can't create metadata on a non-existing entity_guid", 'ERROR');
			return false;
		}
		
		if (!is_scalar($metadata->value)) {
			elgg_log("To set multiple metadata values use ElggEntity::setMetadata", 'ERROR');
			return false;
		}
		
		if ($metadata->id) {
			if ($this->update($metadata)) {
				return $metadata->id;
			}
		}

		if (!$allow_multiple) {
			$id = $this->getIDsByName($metadata->entity_guid, $metadata->name);

			if ($id > 0) {
				$metadata->id = $id;

				if ($this->update($metadata)) {
					return $metadata->id;
				}
			}
		}
		
		static::$iterator++;
		$id = static::$iterator;

		$row = (object) [
			'type' => 'metadata',
			'id' => $id,
			'entity_guid' => $metadata->entity_guid,
			'name' => $metadata->name,
			'value' => $metadata->value,
			'value_type' => $metadata->value_type,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($metadata, $allow_multiple);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(ElggMetadata $metadata) {
		if (!$this->entityTable->exists($metadata->entity_guid)) {
			elgg_log("Can't updated metadata to a non-existing entity_guid", 'ERROR');
			return false;
		}
		
		$id = $metadata->id;
		if (!isset($this->rows[$id])) {
			return false;
		}

		$row = $this->rows[$id];
		$row->name = $metadata->name;
		$row->value = $metadata->value;
		$row->value_type = $metadata->value_type;

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::update($metadata);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll(array $options = array()) {
		$guids = elgg_extract('guids', $options, (array) elgg_extract('guid', $options));
		
		$rows = [];
		foreach ($this->rows as $row) {
			if (empty($guids) || in_array($row->entity_guid, $guids)) {
				$rows[] = $row;
			}
		}
		
		return $rows;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRowsForGuids(array $guids) {
		
		$rows = [];
		foreach ($this->rows as $row) {
			if (in_array($row->entity_guid, $guids)) {
				$rows[] = $row;
			}
		}
		
		return $rows;
	}

	/**
	 * Clear query specs
	 *
	 * @param \stdClass $row Data row
	 * @return void
	 */
	public function clearQuerySpecs(\stdClass $row) {
		if (!isset($this->query_specs[$row->id])) {
			return;
		}
		foreach ($this->query_specs[$row->id] as $spec) {
			$this->db->removeQuerySpec($spec);
		}
	}

	/**
	 * Add query query_specs for a metadata object
	 *
	 * @param \stdClass $row Data row
	 *
	 * @return void
	 */
	public function addQuerySpecs(\stdClass $row) {

		$this->clearQuerySpecs($row);

		$qb = Select::fromTable('metadata');
		$qb->select('*');

		$where = new MetadataWhereClause();
		$where->ids = $row->id;
		$qb->addClause($where);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		// getIDsByName
		$qb = Select::fromTable('metadata');
		$qb->select('id');
		$qb->where($qb->compare('entity_guid', '=', $row->entity_guid, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('name', '=', $row->name, ELGG_VALUE_STRING));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);
		
		$qb = Insert::intoTable('metadata');
		$qb->values([
			'name' => $qb->param($row->name, ELGG_VALUE_STRING),
			'entity_guid' => $qb->param($row->entity_guid, ELGG_VALUE_INTEGER),
			'value' => $qb->param($row->value, $row->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING),
			'value_type' => $qb->param($row->value_type, ELGG_VALUE_STRING),
			'time_created' => $qb->param($row->time_created, ELGG_VALUE_INTEGER),
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'insert_id' => $row->id,
		]);

		$qb = Update::table('metadata');
		$qb->set('name', $qb->param($row->name, ELGG_VALUE_STRING))
			->set('value', $qb->param($row->value, $row->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING))
			->set('value_type', $qb->param($row->value_type, ELGG_VALUE_STRING))
			->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$row->id];
				}
				return [];
			},
		]);

		$qb = Delete::fromTable('metadata');
		$qb->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					unset($this->rows[$row->id]);
					$this->clearQuerySpecs($row);
					return [$row->id];
				}
				return [];
			}
		]);
	}

	/**
	 * Iterate ID
	 * @return int
	 */
	public function iterate() {
		static::$iterator++;
		return static::$iterator;
	}

}
