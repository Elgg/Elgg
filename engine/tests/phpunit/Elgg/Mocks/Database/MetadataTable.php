<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\MetadataTable as DbMetadataTabe;
use ElggMetadata;

/**
 * @group ElggMetadata
 */
class MetadataTable extends DbMetadataTabe {

	/**
	 * @var ElggMetadata
	 */
	public $mocks = [];

	/**
	 * DB query specs
	 * @var array
	 */
	public $specs = [];
	
	/**
	 * @var int
	 */
	public $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function create($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE, $allow_multiple = false) {
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
			'access_id' => (int) $access_id,
			'value_type' => detect_extender_valuetype($value, $this->db->sanitizeString(trim($value_type))),
		];

		$metadata = new \ElggMetadata($row);
		$this->mocks[$id] = $metadata;

		$this->addQuerySpecs($metadata);

		return parent::create($entity_guid, $name, $value, $value_type, $owner_guid, $access_id, $allow_multiple);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($id, $name, $value, $value_type, $owner_guid, $access_id) {
		$metadata = $this->get($id);
		if (!$metadata) {
			return false;
		}

		$metadata->name = $name;
		$metadata->value = $value;
		$metadata->value_type = detect_extender_valuetype($value, $this->db->sanitizeString(trim($value_type)));
		$metadata->owner_guid = $owner_guid;
		$metadata->access_id = $access_id;

		$this->mocks[$id] = $metadata;

		foreach ((array) $this->specs[$id] as $spec) {
			$this->db->removeQuerySpec($spec);
		}
		$this->addQuerySpecs($metadata);

		return parent::update($id, $name, $value, $value_type, $owner_guid, $access_id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id) {
		$metadata = $this->get($id);
		if (!$metadata) {
			return false;
		}

		if ($result = parent::delete($id)) {
			foreach ((array) $this->specs[$id] as $spec) {
				$this->db->removeQuerySpec($spec);
			}
			unset($this->mocks[$id]);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll(array $options = array()) {
		$guids = elgg_extract('guids', $options);
		$rows = [];
		foreach ($this->mocks as $id => $md) {
			if (empty($guids) || in_array($md->entity_guid, $guids)) {
				$rows[] = $md;
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
		foreach ($this->mocks as $id => $md) {
			if (empty($guids) || in_array($md->entity_guid, $guids)) {
				unset($this->mocks[$id]);
				$deleted = true;
			}
		}
		return $deleted;
	}

	/**
	 * Add query specs for a metadata object
	 * 
	 * @param ElggMetadata $metadata Metadata
	 * @return void
	 */
	public function addQuerySpecs(\ElggMetadata $metadata) {

		// Return this metadata object when _elgg_get_metastring_based_objects() is called
		$e_access_sql = _elgg_get_access_where_sql(array('table_alias' => 'e'));
		$md_access_sql = _elgg_get_access_where_sql(array(
			'table_alias' => 'n_table',
			'guid_column' => 'entity_guid',
		));

		$dbprefix = elgg_get_config('dbprefix');
		$sql = "SELECT DISTINCT  n_table.*, n.string as name, v.string as value
			FROM {$dbprefix}metadata n_table
				JOIN {$dbprefix}entities e ON n_table.entity_guid = e.guid
				JOIN {$dbprefix}metastrings n on n_table.name_id = n.id
				JOIN {$dbprefix}metastrings v on n_table.value_id = v.id
				WHERE  (n_table.id IN ({$metadata->id}) AND $md_access_sql) AND $e_access_sql
				ORDER BY n_table.time_created ASC, n_table.id ASC, n_table.id";

		$this->specs[$metadata->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'results' => function() use ($metadata) {
				return [$metadata];
			},
		]);

		$sql = "INSERT INTO {$dbprefix}metadata
				(entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id)
				VALUES (:entity_guid, :name_id, :value_id, :value_type, :owner_guid, :time_created, :access_id)";

		$this->specs[$metadata->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':entity_guid' => $metadata->entity_guid,
				':name_id' => elgg_get_metastring_id($metadata->name),
				':value_id' => elgg_get_metastring_id($metadata->value),
				':value_type' => $metadata->value_type,
				':owner_guid' => $metadata->owner_guid,
				':time_created' => $metadata->time_created,
				':access_id' => $metadata->access_id,
			],
			'insert_id' => $metadata->id,
		]);

		$sql = "UPDATE {$dbprefix}metadata
			SET name_id = :name_id,
			    value_id = :value_id,
				value_type = :value_type,
				access_id = :access_id,
			    owner_guid = :owner_guid
			WHERE id = :id";

		$this->specs[$metadata->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':name_id' => elgg_get_metastring_id($metadata->name),
				':value_id' => elgg_get_metastring_id($metadata->value),
				':value_type' => $metadata->value_type,
				':owner_guid' => $metadata->owner_guid,
				':access_id' => $metadata->access_id,
				':id' => $metadata->id,
			],
			'row_count' => 1,
		]);

		// Enable/disable metadata
		$sql = "UPDATE {$dbprefix}metadata SET enabled = :enabled where id = :id";

		$this->specs[$metadata->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => $metadata->id,
				':enabled' => 'yes',
			],
			'row_count' => 1,
		]);

		$this->specs[$metadata->id][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':id' => $metadata->id,
				':enabled' => 'no',
			],
			'row_count' => 1,
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
