<?php

$engine = dirname(dirname(dirname(__FILE__)));
require_once "$engine/lib/output.php";

/**
 * This requires elgg_get_logged_in_user_guid() in session.php, the access
 * constants defined in entities.php, and elgg_normalize_url() in output.php
 */
class ElggEntityTest extends PHPUnit_Framework_TestCase {

	/** @var ElggEntity */
	protected $obj;

	protected function setUp() {
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
		$this->obj = $this->getMockForAbstractClass('ElggEntity');
		$reflection = new ReflectionClass('ElggEntity');
		$method = $reflection->getMethod('initializeAttributes');
		if (method_exists($method, 'setAccessible')) {
			$method->setAccessible(true);
			$method->invokeArgs($this->obj, array());
		}
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testDefaultAttributes() {
		$this->assertEquals(null, $this->obj->guid);
		$this->assertEquals(null, $this->obj->type);
		$this->assertEquals(null, $this->obj->subtype);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->owner_guid);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->container_guid);
		$this->assertEquals(null, $this->obj->site_guid);
		$this->assertEquals(ACCESS_PRIVATE, $this->obj->access_id);
		$this->assertEquals(null, $this->obj->time_created);
		$this->assertEquals(null, $this->obj->time_updated);
		$this->assertEquals(null, $this->obj->last_action);
		$this->assertEquals('yes', $this->obj->enabled);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingAndGettingAttribute() {
		// Note: before save() subtype returns string, int after
		// see https://github.com/Elgg/Elgg/issues/5920#issuecomment-25246298
		$this->obj->subtype = 'foo';
		$this->assertEquals('foo', $this->obj->subtype);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingIntegerAttributes() {
		foreach (array('access_id', 'owner_guid', 'container_guid') as $name) {
			$this->obj->$name = '77';
			$this->assertSame(77, $this->obj->$name);
		}
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingUnsettableAttributes() {
		foreach (array('guid', 'time_updated', 'last_action') as $name) {
			$this->obj->$name = 'foo';
			$this->assertNotEquals('foo', $this->obj->$name);
		}
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingMetadataNoDatabase() {
		$this->obj->foo = 'test';
		$this->assertEquals('test', $this->obj->foo);
		$this->obj->foo = 'overwrite';
		$this->assertEquals('overwrite', $this->obj->foo);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testGettingNonexistentMetadataNoDatabase() {
		$this->assertNull($this->obj->foo);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSimpleGetters() {
		$this->obj->type = 'foo';
		$this->obj->subtype = 'subtype';
		$this->obj->owner_guid = 77;
		$this->obj->access_id = 2;
		$this->obj->time_created = 123456789;

		$this->assertEquals($this->obj->getGUID(), $this->obj->guid );
		$this->assertEquals($this->obj->getType(), $this->obj->type );

		// Note: before save() subtype returns string, int after
		// see https://github.com/Elgg/Elgg/issues/5920#issuecomment-25246298
		$this->assertEquals($this->obj->getSubtype(), $this->obj->subtype );

		$this->assertEquals($this->obj->getOwnerGUID(), $this->obj->owner_guid );
		$this->assertEquals($this->obj->getAccessID(), $this->obj->access_id );
		$this->assertEquals($this->obj->getTimeCreated(), $this->obj->time_created );
		$this->assertEquals($this->obj->getTimeUpdated(), $this->obj->time_updated );
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testUnsetAttribute() {
		$this->obj->access_id = 2;
		unset($this->obj->access_id);
		$this->assertEquals('', $this->obj->access_id);
	}

	/**
	 * @requires PHP 5.3.2
	 * @expectedException InvalidParameterException
	 */
	public function testSaveWithoutType() {
		$db = $this->getMock('Elgg_Database',
			array('getData', 'getTablePrefix', 'sanitizeString'),
			array(),
			'',
			false
		);
		$db->expects($this->any())
			->method('sanitizeString')
			->will($this->returnArgument(0));
		_elgg_services()->setValue('db', $db);

		// requires type to be set
		$this->obj->save();
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testIsEnabled() {
		$this->assertTrue($this->obj->isEnabled());
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testDisableBeforeSaved() {
		// false on disable because it's not saved yet.
		$this->assertFalse($this->obj->disable());
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testToObject() {
		$keys = array(
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'site_guid',
			'url',
			'read_access',
		);
		sort($keys);

		$object = $this->obj->toObject();
		$object_keys = array_keys(get_object_vars($object));
		sort($object_keys);

		$this->assertEquals($keys, $object_keys);
	}
}
