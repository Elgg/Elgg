<?php
/**
 * Elgg Test ElggObject
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreObjectTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->entity = new ElggObjectTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		unset($this->entity);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		parent::__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggEntityConstructor() {
		$attributes = array();
		$attributes['guid'] = '';
		$attributes['type'] = 'object';
		$attributes['subtype'] = '';
		$attributes['owner_guid'] = get_loggedin_userid();
		$attributes['container_guid'] = get_loggedin_userid();
		$attributes['site_guid'] = 0;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = '';
		$attributes['time_updated'] = '';
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['title'] = '';
		$attributes['description'] = '';

		$this->assertIdentical($this->entity->expose_attributes(), $attributes);
	}
}

class ElggObjectTest extends ElggObject {
	public function expose_attributes() {
		return $this->attributes;
	}
}
