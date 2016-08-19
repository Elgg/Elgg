<?php

namespace Elgg\Mocks\Database;

use Elgg\Config;
use Elgg\Database;
use Elgg\Database\EntityTable as DbEntityTable;
use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggUser;
use Elgg\TestCase;

class EntityTable extends DbEntityTable {

	/**
	 * @var ElggEntity
	 */
	public $mocks = [];

	/**
	 * @var \stdClass[]
	 */
	public $rows = [];

	/**
	 * @var int
	 */
	private $iterator = 100;

	/**
	 * @var array
	 */
	private $query_specs = [];

	/**
	 * {@inheritdoc}
	 */
	public function insertRow(\stdClass $row) {
		if ($result = parent::insertRow($row)) {
			$attributes = (array) $row;
			$this->setup($result, $row->type, null, $attributes);
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateRow($guid, \stdClass $row) {
		$attributes = array_merge((array) $this->rows[$guid], (array) $row);
		$this->addQuerySpecs((object) $attributes);

		if ($result = parent::updateRow($guid, $row)) {
			$this->setup($guid, $attributes['type'], $attributes['subtype'], $attributes);
		}
		return $result;
	}

	/**
	 * Setup a mock entity
	 *
	 * @param int    $guid       GUID of the mock entity
	 * @param string $type       Type of the mock entity
	 * @param string $subtype    Subtype of the mock entity
	 * @param array  $attributes Attributes of the mock entity
	 * @return ElggEntity
	 */
	public function setup($guid, $type, $subtype, array $attributes = []) {
		if (!isset($guid)) {
			$this->iterator++;
			$guid = $this->iterator;
		}

		if ($subtype) {
			$subtype_id = get_subtype_id($type, $subtype);
			if (!$subtype_id) {
				$subtype_id = add_subtype($type, $subtype);
			}
		} else if (isset($attributes['subtype_id'])) {
			$subtype_id = $attributes['subtype_id'];
			$subtype = get_subtype_from_id($subtype_id);
		}

		$attributes['guid'] = $guid;
		$attributes['type'] = $type;
		$attributes['subtype'] = $subtype_id;

		$primary_attributes = array(
			'owner_guid' => 0,
			'container_guid' => 0,
			'site_guid' => 1,
			'access_id' => ACCESS_PUBLIC,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
			'time_updated' => $this->getCurrentTime()->getTimestamp(),
			'last_action' => $this->getCurrentTime()->getTimestamp(),
			'enabled' => 'yes',
		);

		switch ($type) {
			case 'object' :
				$class = ElggObject::class;
				$external_attributes = [
					'title' => null,
					'description' => null,
				];
				break;
			case 'user' :
				$class = ElggUser::class;
				$external_attributes = [
					'name' => "John Doe $guid",
					'username' => "john_doe_$guid",
					'password' => null,
					'salt' => null,
					'password_hash' => null,
					'email' => "john_doe_$guid@example.com",
					'language' => 'en',
					'banned' => "no",
					'admin' => 'no',
					'prev_last_action' => null,
					'last_login' => null,
					'prev_last_login' => null,
				];
				break;
			case 'group' :
				$class = ElggGroup::class;
				$external_attributes = [
					'name' => null,
					'description' => null,
				];
				break;
		}

		$map = array_merge($primary_attributes, $external_attributes, $attributes);

		$attrs = (object) $map;

		if (isset($this->mocks[$guid])) {
			$entity = $this->mocks[$guid];
		} else {
			$entity = new $class($attrs);
		}

		foreach ($map as $name => $value) {
			if (!isset($entity->$name) || $entity->$name != $value) {
				// not an attribute, so needs to be set again
				$entity->$name = $value;
			}
		}

		$this->rows[$guid] = $attrs;
		$this->mocks[$guid] = $entity;

		$this->addQuerySpecs($attrs);

		return $entity;
	}

	/**
	 * Iterate ID
	 * @return int
	 */
	public function iterate() {
		$this->iterator++;
		return $this->iterator;
	}

	/**
	 * Add query specs
	 *
	 * @param \stdClass $row Entity table row
	 * @return void
	 */
	public function addQuerySpecs(\stdClass $row) {

		if (!empty($this->query_specs[$row->guid])) {
			foreach ($this->query_specs[$row->guid] as $spec) {
				$this->db->removeQuerySpec($spec);
			}
		}

		$dbprefix = $this->db->prefix;

		$access = _elgg_get_access_where_sql([
			'table_alias' => '',
		]);

		$sql = "SELECT * FROM {$dbprefix}entities
			WHERE guid = :guid AND $access";

		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':guid' => (int) $row->guid,
			],
			'results' => function() use ($row) {
				return [$row];
			},
		]);

		$sql = "SELECT 1 FROM {$dbprefix}entities
			WHERE guid = :guid";

		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':guid' => (int) $row->guid,
			],
			'results' => function() {
				return [1];
			},
		]);

		$sql = "
			UPDATE {$dbprefix}entities
			SET owner_guid = :owner_guid,
				access_id = :access_id,
				container_guid = :container_guid,
				time_created = :time_created,
				time_updated = :time_updated
			WHERE guid = :guid
		";

		$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':owner_guid' => $row->owner_guid,
				':access_id' => $row->access_id,
				':container_guid' => $row->container_guid,
				':time_created' => $row->time_created,
				':time_updated' => $row->time_updated,
				':guid' => $row->guid,
			],
			'row_count' => 1,
		]);


		switch ($row->type) {
			case 'object' :
				$sql = "SELECT * FROM {$dbprefix}objects_entity
					WHERE guid = :guid";

				$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
					'sql' => $sql,
					'params' => [
						':guid' => (int) $row->guid,
					],
					'results' => function() use ($row) {
						return [
							(object) [
								'guid' => (int) $row->guid,
								'title' => $row->title,
								'description' => $row->description,
							],
						];
					},
				]);

				$sql = "
					UPDATE {$dbprefix}objects_entity
					SET title = :title,
						description = :description
					WHERE guid = :guid
				";

				$this->query_specs[$row->guid][] = $this->db->addQuerySpec([
					'sql' => $sql,
					'params' => [
						':guid' => $row->guid,
						':title' => $row->title,
						':description' => $row->description,
					],
					'row_count' => 1,
				]);
				break;
		}
	}

}
