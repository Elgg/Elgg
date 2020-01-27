<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

/**
 * @group Database
 */
class RelationshipsTableIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var RelationshipsTable
	 */
	private $service;
	
	public function up() {
		$this->service = _elgg_services()->relationshipsTable;
	}
	
	public function down() {
		unset($this->service);
	}
	
	public function testRemoveAllRelationshipsByGUID() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid));
		
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndRelationship() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship'));
		
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndInverse() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, '', true));
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndInverse() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', true));
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndType() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$group1 = $this->createGroup();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship2', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship3', $group1->guid);
		$this->service->add($group1->guid, 'testRelationship', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship2', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship3', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, '', false, 'group'));
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $group1->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship2', $group1->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship3', $group1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship3', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
		$group1->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndType() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$group1 = $this->createGroup();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship2', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship3', $group1->guid);
		$this->service->add($group1->guid, 'testRelationship', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship2', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship3', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', false, 'group'));
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $group1->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $group1->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $group1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship3', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
		$group1->delete();
	}
	
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndInverseAndType() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$group1 = $this->createGroup();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship2', $group1->guid);
		$this->service->add($object1->guid, 'testRelationship3', $group1->guid);
		$this->service->add($group1->guid, 'testRelationship', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship2', $object1->guid);
		$this->service->add($group1->guid, 'testRelationship3', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', true, 'group'));
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $group1->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $group1->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $group1->guid));
		$this->assertFalse($this->service->check($group1->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($group1->guid, 'testRelationship3', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
		$group1->delete();
	}
	
	public function testgetAllRelationshipsByGUID() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship4', $object1->guid);
		
		$relationships = $this->service->getAll($object1->guid);
		$this->assertIsArray($relationships);
		$this->assertCount(3, $relationships);
		
		$object1->delete();
		$object2->delete();
	}
	
	public function testgetAllRelationshipsByGUIDAndInverse() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship4', $object1->guid);
		
		$relationships = $this->service->getAll($object1->guid, true);
		$this->assertIsArray($relationships);
		$this->assertCount(4, $relationships);
		
		$object1->delete();
		$object2->delete();
	}
}
