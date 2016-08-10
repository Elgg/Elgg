<?php

namespace Elgg\Tests;

use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggUser;
use LogicException;

/**
 * Create test doubles for Elgg entities
 * @access private
 * @since 2.2
 */
class EntityMocks {

	/**
	 * @var \Elgg\TestCase
	 */
	private $test;

	/**
	 * @var ElggEntity[]
	 */
	private $mocks;

	/**
	 * @var int
	 */
	private $iterator;

	/**
	 *
	 * @var type @var array
	 */
	private $subtypes = [];

	/**
	 * Constructor
	 *
	 * @param \Elgg\TestCase $test Test case
	 */
	public function __construct(\Elgg\TestCase $test) {
		$this->test = $test;
		$this->iterator = 100; // some random offset
	}

	/**
	 * Return callback for mocking \Elgg\Database\EntityTable
	 * 
	 * @param int    $guid GUID of the mock entity
	 * @param string $type Type the mock entity
	 * @return boolean
	 */
	public function get($guid, $type = '') {
		if (empty($this->mocks[$guid])) {
			return false;
		}
		$entity = $this->mocks[$guid];
		if (!$type) {
			return $entity;
		}
		if ($type && $entity && $entity->getType() == $type) {
			return $entity;
		}
		return false;
	}

	/**
	 * Return callback for mocking \Elgg\Database\EntityTable
	 *
	 * @param int    $guid GUID of the mock entity
	 * @return boolean
	 */
	public function exists($guid) {
		return $guid && array_key_exists($guid, $this->mocks);
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
	protected function setup($guid, $type, $subtype, array $attributes = []) {

		switch ($type) {
			case 'object' :
				$class = ElggObject::class;
				break;
			case 'user' :
				$class = ElggUser::class;
				break;
			case 'group' :
				$class = ElggGroup::class;
				break;
		}

		$entity = $this->test->getMockBuilder($class)
				->setMethods(['getGUID', 'getType', 'getSubtype', '__get', '__set', '__unset'])
				->disableOriginalConstructor()
				->getMock();

		$entity->expects($this->test->any())
				->method('getGUID')
				->will($this->test->returnValue($guid));
		$attributes['guid'] = $guid;


		$entity->expects($this->test->any())
				->method('getType')
				->will($this->test->returnValue($type));
		$attributes['type'] = $type;


		$entity->expects($this->test->any())
				->method('getSubtype')
				->will($this->test->returnValue($subtype));
		$attributes['subtype'] = $subtype;

		$map = [];
		foreach ($attributes as $key => $value) {
			$map[] = [$key, $value];
		}

		$entity->expects($this->test->any())
				->method('__get')
				->will($this->test->returnValueMap($map));

		$this->mocks[$guid] = $entity;

		return $entity;
	}

	/**
	 * Setup a mock user
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggUser
	 */
	public function getUser(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_user';
		return $this->setup($this->iterator, 'user', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggObject
	 */
	public function getObject(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_object';
		return $this->setup($this->iterator, 'object', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggGroup
	 */
	public function getGroup(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_group';
		return $this->setup($this->iterator, 'group', $subtype, $attributes);
	}

	/**
	 * Get SubtypeTable mock
	 * @return \Elgg\Database\SubtypeTable
	 */
	public function getSubtypeTableMock() {

		$mock = $this->test->getMockBuilder(\Elgg\Database\SubtypeTable::class)
				->setMethods([
					'getId',
					'add',
					'getSubtype',
					'getClass',
					'getClassFromId',
					'update',
					'remove',
				])
				->disableOriginalConstructor()
				->getMock();

		$mock->expects($this->test->any())
				->method('getId')
				->will($this->test->returnCallback([$this, 'getSubtypeId']));

		$mock->expects($this->test->any())
				->method('add')
				->will($this->test->returnCallback([$this, 'addSubtype']));

		$mock->expects($this->test->any())
				->method('getSubtype')
				->will($this->test->returnCallback([$this, 'getSubtypeFromId']));

		$mock->expects($this->test->any())
				->method('getClass')
				->will($this->test->returnCallback([$this, 'getSubtypeClass']));

		$mock->expects($this->test->any())
				->method('getClassFromId')
				->will($this->test->returnCallback([$this, 'getSubtypeClassFromId']));

		$mock->expects($this->test->any())
				->method('remove')
				->will($this->test->returnCallback([$this, 'removeSubtype']));

		$mock->expects($this->test->any())
				->method('update')
				->will($this->test->returnCallback([$this, 'updateSubtype']));

		return $mock;
	}

	public function getSubtypeId($type, $subtype) {
		foreach ($this->subtypes as $id => $row) {
			if ($row['type'] == $type && $row['subtype'] == $subtype) {
				return $id;
			}
			return false;
		}
	}

	public function addSubtype($type, $subtype, $class = '') {
		if ($id = $this->getSubtypeId($type, $subtype)) {
			return $id;
		}
		$last = max(array_keys($this->subtypes));
		$last++;

		$this->subtypes[$last] = [
			'type' => $type,
			'subtype' => $subtype,
			'class' => $class,
		];

		return $last;
	}

	public function getSubtypeFromId($id) {
		if (!isset($this->subtypes[$id])) {
			return false;
		}
		return $this->subtypes[$id]['subtype'];
	}

	public function getSubtypeClass($type, $subtype) {
		$id = $this->getSubtypeId($type, $subtype);
		if (empty($this->subtypes[$id]['class'])) {
			return false;
		}
		return $this->subtypes[$id]['class'];
	}

	public function getSubtypeClassFromId($id) {
		if (empty($this->subtypes[$id]['class'])) {
			return false;
		}
		return $this->subtypes[$id]['class'];
	}

	public function removeSubtype($type, $subtype) {
		$id = $this->getSubtypeId($type, $subtype);
		if (!isset($this->subtypes[$id])) {
			return false;
		}
		unset($this->subtypes[$id]);
		return true;
	}

	public function updateSubtype($type, $subtype, $class = '') {
		$id = $this->getSubtypeId($type, $subtype);
		if (!$id) {
			return false;
		}

		$this->subtypes[$id] = [
			'type' => $type,
			'subtype' => $subtype,
			'class' => $class,
		];

		return true;
	}

}
