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
	
	protected $delete_event_counter;
	
	public function up() {
		$this->service = _elgg_services()->relationshipsTable;
		_elgg_services()->events->backup();
		
		$this->delete_event_counter = 0;
		_elgg_services()->events->registerHandler('delete', 'relationship', function(\Elgg\Event $event) {
			$this->delete_event_counter++;
		});
	}
	
	public function down() {
		unset($this->service);
		_elgg_services()->events->restore();
	}
	
	public function removeAllEventToggleProvider() {
		return [
			[true],
			[false],
		];
	}
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUID(bool $trigger_events) {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, '', false, '', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndRelationship(bool $trigger_events) {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', false, '', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
		$this->assertFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndInverse(bool $trigger_events) {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, '', true, '', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndInverse(bool $trigger_events) {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		$this->service->add($object1->guid, 'testRelationship', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship2', $object2->guid);
		$this->service->add($object1->guid, 'testRelationship3', $object2->guid);
		$this->service->add($object2->guid, 'testRelationship', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship2', $object1->guid);
		$this->service->add($object2->guid, 'testRelationship3', $object1->guid);
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', true, '', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship2', $object2->guid));
		$this->assertNotFalse($this->service->check($object1->guid, 'testRelationship3', $object2->guid));
		$this->assertFalse($this->service->check($object2->guid, 'testRelationship', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship2', $object1->guid));
		$this->assertNotFalse($this->service->check($object2->guid, 'testRelationship3', $object1->guid));
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndType(bool $trigger_events) {
		
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
		
		$this->assertTrue($this->service->removeAll($object1->guid, '', false, 'group', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
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
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndType(bool $trigger_events) {
		
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
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', false, 'group', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
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
	
	/**
	 * @dataProvider removeAllEventToggleProvider
	 */
	public function testRemoveAllRelationshipsByGUIDAndRelationshipAndInverseAndType(bool $trigger_events) {
		
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
		
		$this->assertTrue($this->service->removeAll($object1->guid, 'testRelationship', true, 'group', $trigger_events));
		if ($trigger_events) {
			$this->assertGreaterThan(0, $this->delete_event_counter, 'No delete relationship events were triggered');
		} else {
			$this->assertEmpty($this->delete_event_counter, 'Delete relationship events were triggered');
		}
		
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
