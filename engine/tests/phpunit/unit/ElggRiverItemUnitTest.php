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
			'posted' => '1655808864',
		];
		
		$item = new \ElggRiverItem((object) $row);
		
		$object = $item->toObject();
		
		$this->assertEquals($item->id, $object->id);
		$this->assertEquals($item->subject_guid, $object->subject_guid);
		$this->assertEquals($item->target_guid, $object->target_guid);
		$this->assertEquals($item->object_guid, $object->object_guid);
		$this->assertEquals($item->annotation_id, $object->annotation_id);
		$this->assertEquals($item->action_type, $object->action);
		$this->assertEquals($item->view, $object->view);
		$this->assertEquals(date('c', $item->posted), $object->time_posted);
	}
	
	public function testMagicFunctions() {
		$item = new \ElggRiverItem();
		
		// this is an int column, this contains magic to cast to an int
		$item->subject_guid = '123';
		$this->assertTrue(isset($item->subject_guid));
		$this->assertIsInt($item->subject_guid);
		$this->assertEquals(123, $item->subject_guid);
		unset($item->subject_guid);
		$this->assertNull($item->subject_guid);
		
		// test string column
		$item->action_type = 'create';
		$this->assertTrue(isset($item->action_type));
		$this->assertIsString($item->action_type);
		$this->assertEquals('create', $item->action_type);
		unset($item->action_type);
		$this->assertNull($item->action_type);
	}
	
	public function testMagicSetterThrowsException() {
		$item = new \ElggRiverItem();
		
		$this->expectException(\Elgg\Exceptions\RuntimeException::class);
		$item->foo = 'bar';
	}
}
