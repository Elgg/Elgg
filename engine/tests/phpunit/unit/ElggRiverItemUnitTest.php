<?php

use Elgg\UnitTestCase;

class ElggRiverItemUnitTest extends UnitTestCase {
	
	public function testContstructorWithDatabaseRow() {
		$row = [
			'id' => '1',
			'subject_guid' => '2',
			'object_guid' => '3',
			'target_guid' => '4',
			'annotation_id' => '5',
			'action_type' => 'create',
			'view' => 'foo/bar',
			'access_id' => '2',
			'posted' => '1655808864',
		];
		
		$item = new \ElggRiverItem((object) $row);
		
		$this->assertEquals($row['action_type'], $item->action_type);
		$this->assertEquals($row['view'], $item->view);
		
		unset($row['action_type']);
		unset($row['view']);
		
		// check conversion to ints
		foreach ($row as $name => $value) {
			$this->assertIsInt($item->$name);
			$this->assertEquals((int) $value, $item->$name);
		}
	}
	
	public function testGetTypeSubtype() {
		$item = new \ElggRiverItem();
		
		$this->assertEquals('river', $item->getType());
		$this->assertEquals('item', $item->getSubtype());
	}
	
	public function testToObject() {
		$row = [
			'id' => '1',
			'subject_guid' => '2',
			'object_guid' => '3',
			'target_guid' => '4',
			'annotation_id' => '5',
			'action_type' => 'create',
			'view' => 'foo/bar',
			'access_id' => '2',
			'posted' => '1655808864',
		];
		
		$item = new \ElggRiverItem((object) $row);
		
		$object = $item->toObject();
		
		$this->assertEquals($item->id, $object->id);
		$this->assertEquals($item->subject_guid, $object->subject_guid);
		$this->assertEquals($item->target_guid, $object->target_guid);
		$this->assertEquals($item->object_guid, $object->object_guid);
		$this->assertEquals($item->annotation_id, $object->annotation_id);
		$this->assertEquals($item->access_id, $object->read_access);
		$this->assertEquals($item->action_type, $object->action);
		$this->assertEquals($item->view, $object->view);
		$this->assertEquals(date('c', $item->posted), $object->time_posted);
	}
}
