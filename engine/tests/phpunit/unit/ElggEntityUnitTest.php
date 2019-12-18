<?php

/**
 * This requires elgg_get_logged_in_user_guid() in session.php, the access
 * constants defined in entities.php, and elgg_normalize_url() in output.php
 *
 * @group ElggEntity
 * @group UnitTests
 */
class ElggEntityUnitTest extends \Elgg\UnitTestCase {

	/** @var \ElggEntity */
	protected $obj;

	public function up() {
		_elgg_services()->setValue('session', \ElggSession::getMock());
		$this->obj = $this->getMockForAbstractClass('\ElggObject');
		$reflection = new ReflectionClass('\ElggObject');
		$method = $reflection->getMethod('initializeAttributes');
		if (method_exists($method, 'setAccessible')) {
			$method->setAccessible(true);
			$method->invokeArgs($this->obj, array());
		}
	}

	public function down() {

	}

	public function testDefaultAttributes() {
		$this->assertEquals(null, $this->obj->guid);
		$this->assertEquals('object', $this->obj->type);
		$this->assertEquals(null, $this->obj->subtype);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->owner_guid);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->container_guid);
		$this->assertEquals(ACCESS_PRIVATE, $this->obj->access_id);
		$this->assertEquals(null, $this->obj->time_created);
		$this->assertEquals(null, $this->obj->time_updated);
		$this->assertEquals(null, $this->obj->last_action);
		$this->assertEquals('yes', $this->obj->enabled);
	}

	public function testSettingAndGettingAttribute() {
		$this->obj->subtype = 'foo';
		$this->assertEquals('foo', $this->obj->subtype);
	}

	public function testSettingIntegerAttributes() {
		foreach (array('access_id', 'owner_guid', 'container_guid') as $name) {
			$this->obj->$name = '77';
			$this->assertSame(77, $this->obj->$name);
		}
	}

	public function testSettingUnsettableAttributes() {
		foreach (array('guid', 'time_updated', 'last_action') as $name) {
			$this->obj->$name = 'foo';
			$this->assertNotEquals('foo', $this->obj->$name);
		}
	}

	public function testSettingMetadataNoDatabase() {
		$this->obj->foo = 'test';
		$this->assertEquals('test', $this->obj->foo);
		$this->obj->foo = 'overwrite';
		$this->assertEquals('overwrite', $this->obj->foo);
	}

	public function testGettingNonexistentMetadataNoDatabase() {
		$this->assertNull($this->obj->foo);
	}
	
	public function testAnnotationsNoDatabase() {
		$this->obj->annotate('foo', 'bar');
		$this->assertEquals(['bar'], $this->obj->getAnnotations(['annotation_name' => 'foo']));
		
		$this->obj->deleteAnnotations('foo');
		$this->assertEmpty($this->obj->getAnnotations(['annotation_name' => 'foo']));
	}

	public function testSimpleGetters() {
		$this->obj->subtype = 'subtype';
		$this->obj->owner_guid = 77;
		$this->obj->access_id = 2;
		$this->obj->time_created = 123456789;

		$this->assertEquals($this->obj->getGUID(), $this->obj->guid);
		$this->assertEquals($this->obj->getType(), $this->obj->type);

		// Note: before save() subtype returns string, int after
		// see https://github.com/Elgg/Elgg/issues/5920#issuecomment-25246298
		$this->assertEquals($this->obj->getSubtype(), $this->obj->subtype);

		$this->assertEquals($this->obj->getOwnerGUID(), $this->obj->owner_guid);
		$this->assertEquals($this->obj->getAccessID(), $this->obj->access_id);
		$this->assertEquals($this->obj->getTimeCreated(), $this->obj->time_created);
		$this->assertEquals($this->obj->getTimeUpdated(), $this->obj->time_updated);
	}

	/**
	 * @dataProvider unsetSuccessfullProvider
	 */
	public function testUnsetSuccessfullAttribute($attribute, $value) {
		$this->obj->$attribute = $value;
		$this->assertEquals($value, $this->obj->$attribute);
		unset($this->obj->$attribute);
		$this->assertEquals('', $this->obj->$attribute);
	}
	
	public function unsetSuccessfullProvider() {
		return [
			['access_id', 2],

			['type', 'foo'],
			['subtype', 'foo'],
	
			['owner_guid', 1234],
			['container_guid', 1234],
 			['enabled', 6],
		];
	}

	/**
	 * @dataProvider unsetUnsuccessfullProvider
	 */
	public function testUnsetUnsuccessfullAttribute($attribute, $value) {
		$current_value = $this->obj->$attribute;
		$this->obj->$attribute = $value;
		unset($this->obj->$attribute);
		$this->assertEquals($current_value, $this->obj->$attribute);
	}
	
	public function unsetUnsuccessfullProvider() {
		return [
			['guid', 123456],
			['last_action', 1234],
			['time_updated', 1234],
		];
	}

	public function testIsEnabled() {
		$this->assertTrue($this->obj->isEnabled());
	}

	public function testDisableBeforeSaved() {
		// false on disable because it's not saved yet.
		$this->assertFalse($this->obj->disable());
	}

	public function testToObject() {
		$keys = array(
			'guid',
			'title',
			'description',
			'tags',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'url',
			'read_access',
		);
		sort($keys);

		$object = $this->obj->toObject();
		$object_keys = array_keys($object->getArrayCopy());
		sort($object_keys);

		$this->assertEquals($keys, $object_keys);
	}

	public function testLatLong() {

		// Coordinates for Elgg, Switzerland
		$lat = 47.483333;
		$long = 8.866667;

		$this->obj->setLatLong($lat, $long);

		$this->assertEquals($this->obj->getLatitude(), $lat);
		$this->assertEquals($this->obj->getLongitude(), $long);
	}

}
