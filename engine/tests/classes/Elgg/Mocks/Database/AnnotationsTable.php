<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AnnotationsTable as DbAnnotations;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;
use ElggAnnotation;

class AnnotationsTable extends DbAnnotations {

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

	/**
	 * {@inheritdoc}
	 */
	public function get($id) {
		if (empty($this->rows[$id])) {
			return false;
		}

		$annotation = new ElggAnnotation($this->rows[$id]);

		if ($annotation->access_id == ACCESS_PUBLIC) {
			// Public entities are always accessible
			return $annotation;
		}

		$user_guid = isset($user_guid) ? (int) $user_guid : elgg_get_logged_in_user_guid();

		if (_elgg_services()->userCapabilities->canBypassPermissionsCheck($user_guid)) {
			return $annotation;
		}

		if ($user_guid && $user_guid == $annotation->owner_guid) {
			// Owners have access to their own content
			return $annotation;
		}

		if ($user_guid && $annotation->access_id == ACCESS_LOGGED_IN) {
			// Existing users have access to entities with logged in access
			return $annotation;
		}

		return parent::get($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function create(ElggAnnotation $annotation, \ElggEntity $entity) {
		static::$iterator++;
		$id = static::$iterator;

		$row = (object) [
			'type' => 'annotation',
			'id' => $id,
			'entity_guid' => $entity->guid,
			'owner_guid' => $annotation->owner_guid,
			'access_id' => $annotation->access_id,
			'name' => $annotation->name,
			'value' => $annotation->value,
			'value_type' => $annotation->value_type,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
			'enabled' => $annotation->enabled,
		];
		
		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::create($annotation, $entity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(ElggAnnotation $annotation) {
		$id = $annotation->id;
		if (!isset($this->rows[$id])) {
			return false;
		}

		$row = $this->rows[$id];
		$row->name = $annotation->name;
		$row->value = $annotation->value;
		$row->value_type = $annotation->value_type;

		$this->rows[$id] = $row;

		$this->addQuerySpecs($row);

		return parent::update($annotation);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll(array $options = array()) {
		$guids = elgg_extract('guids', $options, (array) elgg_extract('guid', $options));

		$rows = [];
		foreach ($this->rows as $id => $row) {
			if (empty($guids) || in_array($row->entity_guid, $guids)) {
				$rows[] = new ElggAnnotation($row);
			}
		}

		return $rows;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(ElggAnnotation $annotation) {
		parent::delete($annotation);

		if (!isset($this->rows[$annotation->id])) {
			return false;
		}
		$row = $this->rows[$annotation->id];
		$this->clearQuerySpecs($row);

		unset($this->rows[$annotation->id]);

		return true;
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

		$qb = Select::fromTable('annotations');
		$qb->select('*');

		$where = new AnnotationWhereClause();
		$where->ids = $row->id;
		$qb->addClause($where);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}

				return [];
			},
		]);

		$qb = Insert::intoTable('annotations');
		$qb->values([
			'entity_guid' => $qb->param($row->entity_guid, ELGG_VALUE_INTEGER),
			'name' => $qb->param($row->name, ELGG_VALUE_STRING),
			'value' => $qb->param($row->value, $row->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING),
			'value_type' => $qb->param($row->value_type, ELGG_VALUE_STRING),
			'owner_guid' => $qb->param($row->owner_guid, ELGG_VALUE_INTEGER),
			'time_created' => $qb->param($row->time_created, ELGG_VALUE_INTEGER),
			'access_id' => $qb->param($row->access_id, ELGG_VALUE_INTEGER),
		]);

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'insert_id' => $row->id,
		]);

		$qb = Update::table('annotations');
		$qb->set('name', $qb->param($row->name, ELGG_VALUE_STRING))
			->set('value', $qb->param($row->value, $row->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING))
			->set('value_type', $qb->param($row->value_type, ELGG_VALUE_STRING))
			->set('access_id', $qb->param($row->access_id, ELGG_VALUE_INTEGER))
			->set('owner_guid', $qb->param($row->owner_guid, ELGG_VALUE_INTEGER))
			->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$row->id];
				}

				return [];
			},
		]);

		$qb = Delete::fromTable('annotations');
		$qb->where($qb->compare('id', '=', $row->id, ELGG_VALUE_INTEGER));

		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $qb->getSQL(),
			'params' => $qb->getParameters(),
			'results' => function () use ($row) {
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
