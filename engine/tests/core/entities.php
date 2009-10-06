<?php
/**
 * Elgg Test ElggEntities
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreEntityTest extends ElggCoreUnitTest {
	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->entity = new ElggEntityTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		unset($this->entity);
	}

	/**
	 * Tests the protected attributes
	 */
	public function testElggEntityAttributes() {
		$test_attributes = array();
		$test_attributes['guid'] = '';
		$test_attributes['type'] = '';
		$test_attributes['subtype'] = '';
		$test_attributes['owner_guid'] = get_loggedin_userid();
		$test_attributes['container_guid'] = get_loggedin_userid();
		$test_attributes['site_guid'] = 0;
		$test_attributes['access_id'] = ACCESS_PRIVATE;
		$test_attributes['time_created'] = '';
		$test_attributes['time_updated'] = '';
		$test_attributes['enabled'] = 'yes';
		$test_attributes['tables_split'] = 1;
		$test_attributes['tables_loaded'] = 0;

		$this->assertIdentical($this->entity->expose_attributes(), $test_attributes);
	}

	public function testElggEntityGetAndSet() {
		$this->assertIdentical($this->entity->get('access_id'), ACCESS_PRIVATE);
		$this->assertTrue($this->entity->set('access_id', ACCESS_PUBLIC));
		$this->assertIdentical($this->entity->get('access_id'), ACCESS_PUBLIC);
		$this->assertIdentical($this->entity->access_id, ACCESS_PUBLIC);
		unset($this->entity->access_id);
		$this->assertIdentical($this->entity->access_id, '');

		$this->assertFalse($this->entity->set('guid', 'error'));

		$this->assertNull($this->entity->get('non_existent'));
		$this->assertFalse(isset($this->entity->non_existent));
		$this->assertTrue($this->entity->non_existent = 'test');
		$this->assertTrue(isset($this->entity->non_existent));
		$this->assertIdentical($this->entity->non_existent, 'test');
	}
}

// ElggEntity is an abstract class with no abstact methods.
class ElggEntityTest extends ElggEntity {
	public function __construct() {
		$this->initialise_attributes();
	}

	public function expose_attributes() {
		return $this->attributes;
	}
}
