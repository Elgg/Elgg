<?php
/**
 * Elgg Test ElggEntities
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreEntityTest extends ElggCoreUnitTest {

	/**
	 * @var ElggEntityTest
	 */
	protected $entity;

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
		$test_attributes['guid'] = null;
		$test_attributes['type'] = null;
		$test_attributes['subtype'] = null;
		$test_attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$test_attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$test_attributes['site_guid'] = null;
		$test_attributes['access_id'] = ACCESS_PRIVATE;
		$test_attributes['time_created'] = null;
		$test_attributes['time_updated'] = null;
		$test_attributes['last_action'] = null;
		$test_attributes['enabled'] = 'yes';
		ksort($test_attributes);

		$entity_attributes = $this->entity->expose_attributes();
		ksort($entity_attributes);

		$this->assertIdentical($entity_attributes, $test_attributes);
	}

	public function testElggEntityGetAndSetBaseAttributes() {
		// explicitly set and get access_id
		$this->assertIdentical($this->entity->get('access_id'), ACCESS_PRIVATE);
		$this->assertTrue($this->entity->set('access_id', ACCESS_PUBLIC));
		$this->assertIdentical($this->entity->get('access_id'), ACCESS_PUBLIC);

		// check internal attributes array
		$attributes = $this->entity->expose_attributes();
		$this->assertIdentical($attributes['access_id'], ACCESS_PUBLIC);

		// implicitly set and get access_id
		$this->entity->access_id = ACCESS_PRIVATE;
		$this->assertIdentical($this->entity->access_id, ACCESS_PRIVATE);

		// unset access_id
		unset($this->entity->access_id);
		$this->assertIdentical($this->entity->access_id, '');

		// consider helper methods
		$this->assertIdentical($this->entity->getGUID(), $this->entity->guid );
		$this->assertIdentical($this->entity->getType(), $this->entity->type );
		$this->assertIdentical($this->entity->getSubtype(), $this->entity->subtype );
		$this->assertIdentical($this->entity->getOwnerGUID(), $this->entity->owner_guid );
		$this->assertIdentical($this->entity->getAccessID(), $this->entity->access_id );
		$this->assertIdentical($this->entity->getTimeCreated(), $this->entity->time_created );
		$this->assertIdentical($this->entity->getTimeUpdated(), $this->entity->time_updated );
	}

	public function testElggEntityGetAndSetMetadata() {
		// ensure metadata not set
		$this->assertNull($this->entity->non_existent);
		$this->assertFalse(isset($this->entity->non_existent));

		// create metadata
		$this->entity->existent = 'testing';
		$this->assertIdentical($this->entity->existent, 'testing');

		// check metadata set
		$this->assertTrue(isset($this->entity->existent));
		$this->assertIdentical($this->entity->getMetadata('existent'), 'testing');

		// check internal metadata array
		$metadata = $this->entity->expose_metadata();
		$this->assertIdentical($metadata['existent'], array('testing'));
	}

	public function testElggEntityCache() {
		global $ENTITY_CACHE;
		$this->assertIsA($ENTITY_CACHE, 'array');
	}

	public function testElggEntitySaveAndDelete() {

		// unable to delete with no guid
		$this->assertFalse($this->entity->delete());

		// error on save because no type
		try {
			$this->entity->save();
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
		}
	}

	public function testElggEntityDisableAndEnable() {

		// ensure enabled
		$this->assertTrue($this->entity->isEnabled());

		// false on disable because it's not saved yet.
		$this->assertFalse($this->entity->disable());
	}

	// @todo toObject() triggers a plugin hook - we need to remove any callbacks
	public function testElggEntityToObject() {
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

		$object = $this->entity->toObject();
		$object_keys = array_keys(get_object_vars($object));
		sort($keys);
		sort($object_keys);
		$this->assertIdentical($keys, $object_keys);
	}
}

// ElggEntity is an abstract class
class ElggEntityTest extends ElggEntity {
	public function __construct() {
		$this->initializeAttributes();
	}

	public function expose_attributes() {
		return $this->attributes;
	}

	public function expose_metadata() {
		return $this->temp_metadata;
	}

	public function expose_annotations() {
		return $this->temp_annotations;
	}

	public function getDisplayName() {
		return $this->title;
	}

	public function setDisplayName($displayName) {
		$this->title = $displayName;
	}
}
