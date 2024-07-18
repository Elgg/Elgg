<?php

namespace Elgg\Database;

use Elgg\Exceptions\LengthException;

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
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$this->assertTrue($this->service->add($relationship));
	}
	
	public function testAddRelationshipWithIdReturn() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
	}
	
	public function testAddTooLongRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = str_repeat('Foo', RelationshipsTable::RELATIONSHIP_COLUMN_LENGTH);
		$relationship->guid_two = $object2->guid;
		
		$this->expectException(LengthException::class);
		$this->service->add($relationship);
	}
	
	public function testAddDuplicateRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$this->assertTrue($this->service->add($relationship));
		$this->assertFalse($this->service->add($relationship));
	}
	
	public function testAddNonExistingEntityRelationshipFailure() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = 123456789;
		
		$this->assertFalse($this->service->add($relationship));
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = 123456789;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$this->assertFalse($this->service->add($relationship));
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = 123456789;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = 987654321;
		
		$this->assertFalse($this->service->add($relationship));
	}
	
	public function testAddRelationshipPreventByEvent() {
		elgg()->events->backup();
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		elgg()->events->registerHandler('create', 'relationship', function(\Elgg\Event $event) {
			return false;
		});
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$this->assertFalse($this->service->add($relationship));
		
		elgg()->events->restore();
	}
	
	public function testGetRelationshipByID() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertInstanceOf(\ElggRelationship::class, $this->service->get($id));
	}
	
	public function testGetRelationshipByUnknownID() {
		$this->assertNull($this->service->get(123));
	}
	
	public function testDeleteRelationshipByID() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertTrue($this->service->delete($id));
	}
	
	public function testDeleteRelationshipByIDPreventByEvent() {
		elgg()->events->backup();
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		elgg()->events->registerHandler('delete', 'relationship', function(\Elgg\Event $event) {
			return false;
		});
		
		$this->assertFalse($this->service->delete($id));
		
		elgg()->events->restore();
	}
	
	public function testDeleteRelationshipByUnknownID() {
		$this->assertFalse($this->service->delete(123));
	}
	
	public function testCheckRelationship() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testUnknownRelationship', $object2->guid));
	}
	
	public function testRemoveRelationship() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $object1->guid;
		$relationship->relationship = 'testRelationship';
		$relationship->guid_two = $object2->guid;
		
		$id = $this->service->add($relationship, true);
		$this->assertIsInt($id);
		$this->assertGreaterThan(0, $id);
		
		$this->assertFalse($this->service->remove($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->remove($object1->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->remove($object2->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->remove($object1->guid, 'testUnknownRelationship', $object2->guid));
		$this->assertTrue($this->service->remove($object1->guid, 'testRelationship', $object2->guid));
	}
}
