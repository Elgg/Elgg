<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\Annotations as DbAnnotations;
use ElggMetadata;

class Annotations extends DbAnnotations {

	/**
	 * @var ElggMetadata
	 */
	public $mocks = [];

	/**
	 * @var int
	 */
	private $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function get($id) {
		if (empty($this->mocks[$id])) {
			return false;
		}
		return $this->mocks[$id];
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id) {
		if (!isset($this->mocks[$id])) {
			return false;
		}
		unset($this->mocks[$id]);
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE) {
		$entity = get_entity($entity_guid);
		if (!$entity) {
			return false;
		}

		$owner_guid = (int) $owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}

		$this->iterator++;
		$id = $this->iterator;

		$row = (object) [
			'type' => 'annotation',
			'id' => $id,
			'entity_guid' => $entity->guid,
			'owner_guid' => $owner_guid,
			'name' => $name,
			'value' => $value,
			'value_type' => $value_type,
			'time_created' => $this->getCurrentTime()->getTimestamp(),
			'access_id' => (int) $access_id,
		];

		$annotation = new \ElggAnnotation($row);

		$this->mocks[$id] = $annotation;
		return $id;
	}

}
