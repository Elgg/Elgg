<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\MetadataTable as DbMetadataTabe;
use Elgg\Database\Select;
use ElggMetadata;
use stdClass;

/**
 * @group ElggMetadata
 */
class MetadataTable extends DbMetadataTabe {

	/**
	 * @var stdClass[]
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
	public $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function create($entity_guid, $name, $value, $value_type = '', $ignore = null, $allow_multiple = false) {
		$entity = get_entity((int) $entity_guid);
		if (!$entity) {
			return false;
		}

		if (!isset($value)) {
			return false;
		}

		$this->iterator++;
		$id = $this->iterator;

		$row = (object) [
			'type' => 'metadata',
			'id' => $id,
			'entity_guid' => $entity->guid,
			'name' => $name,
			'value' => $value,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
			'value_type' => \ElggExtender::detectValueType($value, trim($value_type)),
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($entity_guid, $name, $value, $value_type, null, $allow_multiple);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($id, $name, $value, $value_type) {
		if (!isset($this->rows[$id])) {
			return false;
		}
		$row = $this->rows[$id];
		$row->name = $name;
		$row->value = $value;
		$row->value_type = \ElggExtender::detectValueType($value, trim($value_type));

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::update($id, $name, $value, $value_type);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll(array $options = array()) {
		$guids = elgg_extract('guids', $options, (array) elgg_extract('guid', $options));
		
		$rows = [];
		foreach ($this->rows as $id => $row) {
			if (empty($guids) || in_array($row->entity_guid, $guids)) {
				$rows[] = new ElggMetadata($row);
			}
		}
		
		return $rows;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id) {
		if (!isset($this->rows[$id])) {
			return false;
		}
		$row = $this->rows[$id];
		$this->clearQuerySpecs($row);
		
		unset($this->rows[$id]);
		
		return true;
	}

	/**
	 * Clear query specs
	 *
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function clearQuerySpecs(stdClass $row) {
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
	 * @param stdClass $row Data row
	 * @return void
	 */
	public function addQuerySpecs(stdClass $row) {

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

		$dbprefix = elgg_get_config('dbprefix');
		$sql = "INSERT INTO {$dbprefix}metadata
				(entity_guid, name, value, value_type, time_created)
				VALUES (:entity_guid, :name, :value, :value_type, :time_created)";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':entity_guid' => $row->entity_guid,
				':name' => $row->name,
				':value' => $row->value,
				':value_type' => $row->value_type,
				':time_created' => (int) $row->time_created,
			],
			'insert_id' => $row->id,
		]);

		$sql = "UPDATE {$dbprefix}metadata
			SET name = :name,
			    value = :value,
				value_type = :value_type
			WHERE id = :id";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':name' => $row->name,
				':value' => $row->value,
				':value_type' => $row->value_type,
				':id' => $row->id,
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$row->id];
				}
				return [];
			},
		]);

		// Delete
		$sql = "DELETE FROM {$dbprefix}metadata WHERE id = :id";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => $row->id,
			],
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
		$this->iterator++;
		return $this->iterator;
	}

}
