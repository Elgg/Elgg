<?php

namespace Elgg\Tests;

use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggUser;
use LogicException;
use PHPUnit_Framework_TestCase;

/**
 * Create test doubles for Elgg entities
 * @access private
 * @since 2.2
 */
class EntityMocks {
	
	/**
	 * @var PHPUnit_Framework_TestCase
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
	 * Constructor
	 *
	 * @param PHPUnit_Framework_TestCase $test Test case
	 */
	public function __construct(PHPUnit_Framework_TestCase $test) {
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
}
