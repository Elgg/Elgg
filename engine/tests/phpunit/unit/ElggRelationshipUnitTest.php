<?php

/**
 * @group UnitTests
 * @group ElggData
 */
class ElggRelationshipUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testSettingAndGettingAttribute() {
		$obj = $this->getRelationshipMock();
		$obj->relationship = 'hasSister';
		$this->assertEquals('hasSister', $obj->relationship);
	}

	public function testGettingNonexistentAttribute() {
		$obj = $this->getRelationshipMock();
		$this->assertNull($obj->foo);
	}

	protected function getRelationshipMock() {
		// do not call constructor because it would cause deprecation warnings
		// and deprecation is not test-friendly yet.
		return $this->getMockForAbstractClass('\ElggRelationship', array(), '', false);
	}

	public function createRelationship() {
		$subject = $this->createUser();
		$object = $this->createObject();

		$id = add_entity_relationship($subject->guid, 'foo', $object->guid);
		return check_entity_relationship($subject->guid, 'foo', $object->guid);
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

		unset($relationship['access_id']);
	}

	public function testIsLoggable() {
		$relationship = $this->createRelationship();

		$this->assertEquals($relationship->id, $relationship->getSystemLogID());
		$this->assertEquals($relationship, $relationship->getObjectFromID($relationship->id));
	}
}
