<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\MetadataTable as DbMetadataTabe;
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
	public function create($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $ignore = null, $allow_multiple = false) {
		$entity = get_entity((int) $entity_guid);
		if (!$entity) {
			return false;
		}

		if (!isset($value)) {
			return false;
		}

		$owner_guid = (int) $owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}

		$this->iterator++;
		$id = $this->iterator;

		$time = $this->getCurrentTime()->getTimestamp();

		$row = (object) [
			'type' => 'metadata',
			'id' => $id,
			'entity_guid' => $entity->guid,
			'owner_guid' => $owner_guid,
			'name' => $name,
			'value' => $value,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
			'access_id' => ACCESS_PUBLIC,
			'value_type' => \ElggExtender::detectValueType($value, trim($value_type)),
		];

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($entity_guid, $name, $value, $value_type, $owner_guid, null, $allow_multiple);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($id, $name, $value, $value_type, $owner_guid) {
		if (!isset($this->rows[$id])) {
			return false;
		}
		$row = $this->rows[$id];
		$row->name = $name;
		$row->value = $value;
		$row->value_type = \ElggExtender::detectValueType($value, trim($value_type));
		$row->owner_guid = $owner_guid;
		$row->access_id = ACCESS_PUBLIC;

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::update($id, $name, $value, $value_type, $owner_guid);
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
	public function deleteAll(array $options = array()) {
		$guids = elgg_extract('guids', $options);
		$deleted = false;
		foreach ($this->rows as $id => $row) {
			if (empty($guids) || in_array($row->entity_guid, $guids)) {
				$this->clearQuerySpecs($this->rows[$id]);
				$deleted = true;
				unset($this->rows[$id]);
			}
		}
		return $deleted;
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

		// Return this metadata object when _elgg_get_metastring_based_objects() is called
		$e_access_sql = _elgg_get_access_where_sql(array('table_alias' => 'e'));
		
		$dbprefix = elgg_get_config('dbprefix');
		$sql = "SELECT DISTINCT  n_table.*
			FROM {$dbprefix}metadata n_table
				JOIN {$dbprefix}entities e ON n_table.entity_guid = e.guid
				WHERE  (n_table.id IN ({$row->id})) AND $e_access_sql
				ORDER BY n_table.time_created ASC, n_table.id ASC, n_table.id";
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				return [];
			},
		]);

		$sql = "INSERT INTO {$dbprefix}metadata
				(entity_guid, name, value, value_type, owner_guid, time_created, access_id)
				VALUES (:entity_guid, :name, :value, :value_type, :owner_guid, :time_created, :access_id)";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':entity_guid' => $row->entity_guid,
				':name' => $row->name,
				':value' => $row->value,
				':value_type' => $row->value_type,
				':owner_guid' => $row->owner_guid,
				':time_created' => $row->time_created,
				':access_id' => ACCESS_PUBLIC,
			],
			'insert_id' => $row->id,
		]);

		$sql = "UPDATE {$dbprefix}metadata
			SET name = :name,
			    value = :value,
				value_type = :value_type,
				access_id = :access_id,
			    owner_guid = :owner_guid
			WHERE id = :id";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':name' => $row->name,
				':value' => $row->value,
				':value_type' => $row->value_type,
				':owner_guid' => $row->owner_guid,
				':access_id' => ACCESS_PUBLIC,
				':id' => $row->id,
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$row->id];
				}
				return [];
			},
		]);

		// Enable/disable metadata
		$sql = "UPDATE {$dbprefix}metadata SET enabled = :enabled where id = :id";

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => $row->id,
				':enabled' => 'yes',
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->rows[$row->id]->enabled = 'yes';
					return [$row->id];
				}
				return [];
			}
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => $row->id,
				':enabled' => 'no',
			],
			'results' => function() use ($row) {
				if (isset($this->rows[$row->id])) {
					$this->rows[$row->id]->enabled = 'no';
					return [$row->id];
				}
				return [];
			}
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
