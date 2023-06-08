<?php

namespace Elgg\SystemLog;

use Elgg\UnitTestCase;

class SystemLogEntryUnitTest extends UnitTestCase {
	
	protected \ElggObject $object;
	protected \stdClass $row;
	
	public function up() {
		$this->startPlugin();
	}
	
	public function testConstructor() {
		$int_columns = [
			'id',
			'object_id',
			'performed_by_guid',
			'owner_guid',
			'access_id',
			'time_created',
		];
		
		$entry = $this->getEntry();
		foreach ($this->row as $key => $expected) {
			if (in_array($key, $int_columns)) {
				// ints get converted in the constructor
				$this->assertIsInt($entry->$key);
				$this->assertEquals((int) $expected, $entry->$key);
			} else {
				$this->assertEquals($expected, $entry->$key);
			}
		}
	}
	
	public function testMagicFunctions() {
		$entry = $this->getEntry();
		
		// int value (should be converted to int)
		$entry->id = '4567';
		$this->assertIsInt($entry->id);
		$this->assertEquals(4567, $entry->id);
		$this->assertTrue(isset($entry->id));
		unset($entry->id);
		$this->assertNull($entry->id);
		
		// string value
		$entry->object_class = 'ElggBlog';
		$this->assertEquals('ElggBlog', $entry->object_class);
		$this->assertTrue(isset($entry->object_class));
		unset($entry->object_class);
		$this->assertNull($entry->object_class);
	}
	
	public function testSetUnsupportedAttributeThrowsException() {
		$entry = $this->getEntry();
		
		$this->expectException(\Elgg\Exceptions\RuntimeException::class);
		$entry->foo = 'bar';
	}
	
	public function testGetObject() {
		$entry = $this->getEntry();
		
		$fetched_object = $entry->getObject();
		$this->assertEquals($this->object, $fetched_object);
		
		$entry->object_id = -1;
		$this->assertFalse($entry->getObject());
	}
	
	protected function getEntry(): SystemLogEntry {
		$this->object = $this->createObject();
		
		$this->row = (object) [
			'id' => '1234',
			'object_id' => "{$this->object->guid}",
			'object_class' => get_class($this->object),
			'object_type' => $this->object->type,
			'object_subtype' => $this->object->subtype,
			'event' => 'create',
			'performed_by_guid' => "{$this->object->owner_guid}",
			'owner_guid' => "{$this->object->owner_guid}",
			'access_id' => "{$this->object->access_id}",
			'enabled' => $this->object->enabled,
			'time_created' => "{$this->object->time_created}",
			'ip_address' => '127.0.0.1',
		];
		
		return new SystemLogEntry($this->row);
	}
}
