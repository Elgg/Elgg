<?php

namespace Elgg\Mocks\Database;

use Elgg\Database;
use Elgg\Database\Annotations as DbAnnotations;
use Elgg\EventsService;
use Elgg\TestCase;
use ElggAnnotation;
use ElggMetadata;
use ElggSession;

class Annotations extends DbAnnotations {

	/**
	 * @var ElggMetadata
	 */
	public $mocks = [];

	/**
	 *
	 * @var TestCase	private $test;

	  /**
	 * @var int
	 */
	private $iterator = 100;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(Database $db, ElggSession $session, EventsService $events) {
		parent::__construct($db, $session, $events);
		$this->test = TestCase::getInstance();
	}

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

		$this->iterator++;
		$id = $this->iterator;

		$row = (object) [
			'type' => 'annotation',
			'id' => $id,
			'entity_guid' => $entity->guid,
			'owner_guid' => $owner_guid ? : $entity->owner_guid,
			'name' => $name,
			'value' => $value,
			'value_type' => $value_type,
			'time_created' => time(),
			'access_id' => $access_id,
		];

		$annotation = new \ElggAnnotation($row);

		$this->mocks[$id] = $annotation;
		return $id;
	}

}
