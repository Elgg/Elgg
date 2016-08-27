<?php

namespace Elgg\Database;

/**
 * @group Database
 */
class SubtypeTableTest extends \Elgg\TestCase {

	/**
	 * @var \Elgg\Mocks\Database
	 */
	private $db;

	public function setUp() {

		$this->db = $this->mocks()->db;
		_elgg_services()->setValue('db', $this->db);
		_elgg_services()->setValue('subtypeTable', new SubtypeTable($this->db));
	}

	public function fetchAllResults() {
		return [
			(object) [
				'id' => 1,
				'type' => 'object',
				'subtype' => 'foo',
				'class' => 'FooObject',
			],
			(object) [
				'id' => 2,
				'type' => 'object',
				'subtype' => 'foo2',
				'class' => 'Foo2Object',
			],
		];
	}

	public function setupFetchAllQuery() {
		$dbprefix = elgg_get_config('dbprefix');
		$this->db->addQuerySpec([
			'sql' => "SELECT * FROM {$dbprefix}entity_subtypes",
			'results' => [$this, 'fetchAllResults'],
		]);
	}

	public function testCanSetCachedValues() {
		$cache = [
			1 => (object) [
				'id' => 1,
				'type' => 'object',
				'subtype' => 'foo',
				'class' => 'FooObject',
			],
		];

		_elgg_services()->subtypeTable->setCachedValues($cache);
		$this->assertEquals(1, _elgg_services()->subtypeTable->getId('object', 'foo'));
	}

	public function testCanLoadSubtypeTable() {

		$this->setupFetchAllQuery();

		$this->assertEquals(1, _elgg_services()->subtypeTable->getId('object', 'foo'));
		$this->assertFalse(_elgg_services()->subtypeTable->getId('object', 'bar'));
		$this->assertFalse(_elgg_services()->subtypeTable->getId('object', '')); // empty subtype

		$this->assertEquals('foo', _elgg_services()->subtypeTable->getSubtype(1));
		$this->assertFalse(_elgg_services()->subtypeTable->getSubtype(999));
		$this->assertEquals('', _elgg_services()->subtypeTable->getSubtype(0));

		$this->assertEquals('FooObject', _elgg_services()->subtypeTable->getClass('object', 'foo'));
		$this->assertNull(_elgg_services()->subtypeTable->getClass('object', 'bar'));

		$this->assertEquals('FooObject', _elgg_services()->subtypeTable->getClassFromId(1));
		$this->assertNull(_elgg_services()->subtypeTable->getClassFromId(999));
		$this->assertNull(_elgg_services()->subtypeTable->getClassFromId(0));
	}

	public function testCanAddSubtype() {

		$dbprefix = elgg_get_config('dbprefix');

		$type = 'object';
		$subtype = 'bar';
		$class = '\BarObject';

		$this->db->addQuerySpec([
			'sql' => "
				INSERT INTO {$dbprefix}entity_subtypes
				(type,  subtype,  class) VALUES
				(:type, :subtype, :class)
			",
			'params' => [
				':type' => $type,
				':subtype' => $subtype,
				':class' => $class,
			],
			'insert_id' => 3,
		]);

		$this->assertSame(3, _elgg_services()->subtypeTable->add($type, $subtype, $class));
		
		$this->assertEquals(0, _elgg_services()->subtypeTable->add($type, '', $class)); // empty subtype
	}

	public function testCanUpdateSubtype() {

		$dbprefix = elgg_get_config('dbprefix');

		$this->setupFetchAllQuery();

		$type = 'object';
		$subtype = 'foo';
		$new_class = 'FooObjectX';

		$this->db->addQuerySpec([
			'sql' => "
				UPDATE {$dbprefix}entity_subtypes
				SET type = :type, subtype = :subtype, class = :class
				WHERE id = :id
			",
			'params' => [
				':type' => $type,
				':subtype' => $subtype,
				':class' => $new_class,
				':id' => 1,
			],
			'row_count' => 1,
		]);

		$this->assertTrue(_elgg_services()->subtypeTable->update($type, $subtype, $new_class));

		// unregistered subtype
		$this->assertFalse(_elgg_services()->subtypeTable->update($type, 'unregistered', $new_class));
	}

	public function testCanRemoveSubtype() {

		$dbprefix = elgg_get_config('dbprefix');

		$this->setupFetchAllQuery();

		$type = 'object';
		$subtype = 'foo';

		$this->db->addQuerySpec([
			'sql' => "
				DELETE FROM {$dbprefix}entity_subtypes
				WHERE type = :type AND subtype = :subtype
			",
			'params' => [
				':type' => $type,
				':subtype' => $subtype,
			],
			'row_count' => 1,
		]);

		$this->assertTrue(_elgg_services()->subtypeTable->remove($type, $subtype));

		$this->db->addQuerySpec([
			'sql' => "
				DELETE FROM {$dbprefix}entity_subtypes
				WHERE type = :type AND subtype = :subtype
			",
			'params' => [
				':type' => $type,
				':subtype' => 'unregistered',
			],
			'row_count' => 0,
		]);

		$this->assertFalse(_elgg_services()->subtypeTable->remove($type, 'unregistered'));
	}

}
