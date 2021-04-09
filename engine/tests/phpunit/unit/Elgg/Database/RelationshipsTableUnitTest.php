<?php

namespace Elgg\Database;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * @group UnitTests
 */
class RelationshipsTableUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var RelationshipsTable
	 */
	private $service;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		$this->service = _elgg_services()->relationshipsTable;
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		unset($this->service);
	}
	
	public function testAddRelationship() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$success = $this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->assertTrue($success);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testAddRelationshipWithIdReturn() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testAddTooLongRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$str = str_repeat('Foo', RelationshipsTable::RELATIONSHIP_COLUMN_LENGTH);
		
		$this->expectException(InvalidArgumentException::class);
		$this->service->add($object1->guid, $str, $object2->guid);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testAddDuplicateRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$success = $this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->assertTrue($success);
		
		$failure = $this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->assertFalse($failure);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testAddNonExistingEntityRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$failure = $this->service->add($object1->guid, 'testRelationship', 123456789);
		$this->assertFalse($failure);
		
		$failure = $this->service->add(123456789, 'testRelationship', $object2->guid);
		$this->assertFalse($failure);
		
		$failure = $this->service->add(123456789, 'testRelationship', 987654321);
		$this->assertFalse($failure);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testAddRelationshipPreventByEvent() {
		
		elgg()->events->backup();
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		elgg()->events->registerHandler('create', 'relationship', function(\Elgg\Event $event) {
			return false;
		});
		
		$failure = $this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->assertFalse($failure);
		
		elgg()->events->restore();
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testGetRelationshipByID() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$relationship = $this->service->get($id);
		$this->assertInstanceOf(\ElggRelationship::class, $relationship);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testGetRelationshipByUnknownID() {
		$this->assertFalse($this->service->get(123));
	}
	
	public function testDeleteRelationshipByID() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$success = $this->service->delete($id);
		$this->assertTrue($success);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testDeleteRelationshipByIDPreventByEvent() {
		
		elgg()->events->backup();
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		elgg()->events->registerHandler('delete', 'relationship', function(\Elgg\Event $event) {
			return false;
		});
		
		$failure = $this->service->delete($id);
		$this->assertFalse($failure);
		
		elgg()->events->restore();
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testDeleteRelationshipByIDNotPreventedByEvent() {
		
		elgg()->events->backup();
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		elgg()->events->registerHandler('delete', 'relationship', function(\Elgg\Event $event) {
			return false;
		});
		
		$success = $this->service->delete($id, false);
		$this->assertTrue($success);
		
		elgg()->events->restore();
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testDeleteRelationshipByUnknownID() {
		$this->assertFalse($this->service->delete(123));
	}
	
	public function testCheckRelationship() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testUnknownRelationship', $object2->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testRemoveRelationship() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$id = $this->service->add($object1->guid, 'testRelationship', $object2->guid, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertFalse($this->service->remove($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->remove($object1->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->remove($object2->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->remove($object1->guid, 'testUnknownRelationship', $object2->guid));
		$this->assertTrue($this->service->remove($object1->guid, 'testRelationship', $object2->guid));
		
		$object1->delete();
		$object2->delete();
	}
}
