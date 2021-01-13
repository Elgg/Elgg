<?php

/**
 * @group UnitTests
 * @group ElggData
 */
class ElggRelationshipUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggEntity[] Entities created during the tests
	 */
	protected $created_entities = [];
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		// cleanup created entities
		foreach ($this->created_entities as $entity) {
			$entity->delete();
		}
	}

	/**
	 * Get an empty relationship
	 *
	 * @return \ElggRelationship
	 */
	protected function getRelationshipMock() {
		return new \ElggRelationship(new stdClass());
	}
	
	/**
	 * Create a relationship with set attributes
	 *
	 * @return \ElggRelationship|false
	 */
	protected function createRelationship() {
		$subject = $this->createUser();
		$object = $this->createObject();
		
		$this->created_entities[] = $subject;
		$this->created_entities[] = $object;
		
		if (!add_entity_relationship($subject->guid, 'foo', $object->guid)) {
			return false;
		}
		
		return check_entity_relationship($subject->guid, 'foo', $object->guid);
	}
	
	/**
	 * @dataProvider setterDataProvider
	 */
	public function testSettingAndGettingAttribute($name, $value, $expected) {
		$rel = $this->getRelationshipMock();
		
		$rel->$name = $value;
		$this->assertEquals($expected, $rel->$name);
	}
	
	public function setterDataProvider() {
		return [
			['id', 123, null],
			['guid_one', 123, 123],
			['guid_one', '123', 123],
			['relationship', 'foo', 'foo'],
			['guid_two', 123, 123],
			['guid_two', '123', 123],
			['time_created', time(), null],
			['foo', 'bar', null],
		];
	}

	public function testGettingNonexistentAttribute() {
		$obj = $this->getRelationshipMock();
		$this->assertNull($obj->foo);
	}

	public function testCanExport() {
		$relationship = $this->createRelationship();

		$export = $relationship->toObject();

		$this->assertEquals($relationship->id, $export->id);
		$this->assertEquals($relationship->relationship, $export->relationship);
		$this->assertEquals($relationship->guid_one, $export->subject_guid);
		$this->assertEquals($relationship->guid_two, $export->object_guid);
		$this->assertEquals($relationship->time_created, $export->getTimeCreated()->getTimestamp());
	}

	public function testCanSerialize() {
		$relationship = $this->createRelationship();

		$data = serialize($relationship);

		$unserialized_site = unserialize($data);

		$this->assertEquals($relationship, $unserialized_site);
	}

	public function testCanArrayAccessAttributes() {
		$relationship = $this->createRelationship();

		$this->assertEquals($relationship->guid, $relationship['guid']);

		foreach ($relationship as $attr => $value) {
			$this->assertEquals($relationship->$attr, $relationship[$attr]);
		}
	}

	public function testIsLoggable() {
		$relationship = $this->createRelationship();

		$this->assertEquals($relationship->id, $relationship->getSystemLogID());
		$this->assertEquals($relationship, $relationship->getObjectFromID($relationship->id));
	}
	
	public function testContructorOnlySetsPrimaryAttributes() {
		$row = new stdClass();
		$row->id = 123;
		$row->guid_one = 456;
		$row->relationship = 'foo';
		$row->guid_two = 789;
		$row->time_created = time();
		$row->foo = 'bar';
		
		$relationship = new \ElggRelationship($row);
		
		foreach (['id', 'guid_one', 'relationship', 'guid_two', 'time_created'] as $attr) {
			$this->assertEquals($row->$attr, $relationship->$attr);
		}
		
		$this->assertNull($relationship->foo);
	}
	
	public function testSettingUnchangedData() {
		$relationship = $this->createRelationship();
		
		foreach ($relationship as $attribute => $value) {
			$relationship->$attribute = $value;
			$this->assertEmpty($relationship->getOriginalAttributes());
			
			// check for int casting
			$relationship->$attribute = (string) $value;
			$this->assertEmpty($relationship->getOriginalAttributes());
		}
	}
	
	/**
	 * @dataProvider originalAttributesProvider
	 */
	public function testOriginalAttributesOnChange($name, $value, bool $should_change) {
		$relationship = $this->createRelationship();
		
		$relationship->$name = $value;
		if ($should_change) {
			$this->assertArrayHasKey($name, $relationship->getOriginalAttributes());
		} else {
			$this->assertEmpty($relationship->getOriginalAttributes());
		}
	}
	
	public function originalAttributesProvider() {
		return [
			['id', 123, false],
			['guid_one', 123, true],
			['guid_one', '123', true],
			['relationship', 'bar', true],
			['guid_two', 123, true],
			['guid_two', '123', true],
			['time_created', time(), false],
		];
	}
	
	public function testSaveAfterChange() {
		$relationship = $this->createRelationship();
		
		$current_id = $relationship->id;
		
		$relationship->relationship = 'bar';
		$this->assertTrue($relationship->save());
		$this->assertNotEquals($current_id, $relationship->id);
	}
}
