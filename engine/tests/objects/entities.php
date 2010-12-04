<?php
/**
 * Elgg Test ElggEntities
 *
 * @package Elgg
 * @subpackage Test
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
		$this->swallowErrors();
		unset($this->entity);
	}

	/**
	 * Tests the protected attributes
	 */
	public function testElggEntityAttributes() {
		$test_attributes = array();
		$test_attributes['guid'] = NULL;
		$test_attributes['type'] = NULL;
		$test_attributes['subtype'] = NULL;
		$test_attributes['owner_guid'] = get_loggedin_userid();
		$test_attributes['container_guid'] = get_loggedin_userid();
		$test_attributes['site_guid'] = NULL;
		$test_attributes['access_id'] = ACCESS_PRIVATE;
		$test_attributes['time_created'] = NULL;
		$test_attributes['time_updated'] = NULL;
		$test_attributes['last_action'] = NULL;
		$test_attributes['enabled'] = 'yes';
		$test_attributes['tables_split'] = 1;
		$test_attributes['tables_loaded'] = 0;
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

		// unable to directly set guid
		$this->assertFalse($this->entity->set('guid', 'error'));
		$this->entity->guid = 'error';
		$this->assertNotEqual($this->entity->guid, 'error');

		// fail on non-attribute
		$this->assertNull($this->entity->get('non_existent'));

		// consider helper methods
		$this->assertIdentical($this->entity->getGUID(), $this->entity->guid );
		$this->assertIdentical($this->entity->getType(), $this->entity->type );
		$this->assertIdentical($this->entity->getSubtype(), $this->entity->subtype );
		$this->assertIdentical($this->entity->getOwnerGUID(), $this->entity->owner_guid );
		$this->assertIdentical($this->entity->getAccessID(), $this->entity->access_id );
		$this->assertIdentical($this->entity->getTimeCreated(), $this->entity->time_created );
		$this->assertIdentical($this->entity->getTimeUpdated(), $this->entity->time_updated );
	}

	public function testElggEntityGetAndSetMetaData() {
		// ensure metadata not set
		$this->assertNull($this->entity->get('non_existent'));
		$this->assertFalse(isset($this->entity->non_existent));

		// create metadata
		$this->assertTrue($this->entity->non_existent = 'testing');

		// check metadata set
		$this->assertTrue(isset($this->entity->non_existent));
		$this->assertIdentical($this->entity->non_existent, 'testing');
		$this->assertIdentical($this->entity->getMetaData('non_existent'), 'testing');

		// check internal metadata array
		$metadata = $this->entity->expose_metadata();
		$this->assertIdentical($metadata['non_existent'], 'testing');
	}

	public function testElggEnityGetAndSetAnnotations() {
		$this->assertFalse(array_key_exists('non_existent', $this->entity->expose_annotations()));
		$this->assertFalse($this->entity->getAnnotations('non_existent'));

		// set and check temp annotation
		$this->assertTrue($this->entity->annotate('non_existent', 'testing'));
		$this->assertIdentical($this->entity->getAnnotations('non_existent'), 'testing');
		$this->assertTrue(array_key_exists('non_existent', $this->entity->expose_annotations()));

		// save entity and check for annotation
		$this->entity->subtype = 'testing';
		$this->save_entity();
		$this->assertFalse(array_key_exists('non_existent', $this->entity->expose_annotations()));
		$annotations = $this->entity->getAnnotations('non_existent');
		$this->assertIsA($annotations[0], 'ElggAnnotation');
		$this->assertIdentical($annotations[0]->name, 'non_existent');
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 1);
		
		$this->assertIdentical($annotations, get_annotations($this->entity->getGUID()));
		$this->assertIdentical($annotations, get_annotations($this->entity->getGUID(), 'site'));
		$this->assertIdentical($annotations, get_annotations($this->entity->getGUID(), 'site', 'testing'));
		$this->assertIdentical(FALSE, get_annotations($this->entity->getGUID(), 'site', 'fail'));

		//  clear annotation
		$this->assertTrue($this->entity->clearAnnotations());
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 0);
		
		$this->assertIdentical(FALSE, get_annotations($this->entity->getGUID()));
		$this->assertIdentical(FALSE, get_annotations($this->entity->getGUID(), 'site'));
		$this->assertIdentical(FALSE, get_annotations($this->entity->getGUID(), 'site', 'testing'));

		// clean up
		$this->assertTrue($this->entity->delete());
	}

	public function testElggEntityCache() {
		global $ENTITY_CACHE;
		$this->assertIsA($ENTITY_CACHE, 'array');
	}

	public function testElggEntitySaveAndDelete() {
		global $ENTITY_CACHE;

		// unable to delete with no guid
		$this->assertFalse($this->entity->delete());

		// error on save
		try {
			$this->entity->save();
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$this->assertIdentical($e->getMessage(), elgg_echo('InvalidParameterException:EntityTypeNotSet'));
		}

		// set elements
		$this->entity->type = 'site';
		$this->entity->non_existent = 'testing';

		// save
		$this->AssertEqual($this->entity->getGUID(), 0);
		$guid = $this->entity->save();
		$this->AssertNotEqual($guid, 0);

		// check guid
		$this->AssertEqual($this->entity->getGUID(), $guid);
		$attributes = $this->entity->expose_attributes();
		$this->AssertEqual($attributes['guid'], $guid);
		$this->AssertIdentical($ENTITY_CACHE[$guid], $this->entity);

		// check metadata
		$metadata = $this->entity->expose_metadata();
		$this->AssertFalse(in_array('non_existent', $metadata));
		$this->AssertEqual($this->entity->get('non_existent'), 'testing');

		// clean up with delete
		$this->assertTrue($this->entity->delete());
	}

	public function testElggEntityDisableAndEnable() {
		global $CONFIG;

		// ensure enabled
		$this->assertTrue($this->entity->isEnabled());

		// false on disable
		$this->assertFalse($this->entity->disable());

		// save and disable
		$this->save_entity();
		$this->assertTrue($this->entity->disable());

		// ensure disabled by comparing directly with database
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertIdentical($entity->enabled, 'no');

		// re-enable for deletion to work
		$this->assertTrue($this->entity->enable());
		$this->assertTrue($this->entity->delete());
	}
	
	public function testElggEntityMetadata() {
		// let's delte a non-existent metadata
		$this->assertFalse($this->entity->clearMetaData('important'));
		
		// let's add the meatadata
		$this->assertTrue($this->entity->important = 'indeed!');
		$this->assertTrue($this->entity->less_important = 'true, too!');
		$this->save_entity();
		
		// test deleting incorrectly
		// @link http://trac.elgg.org/ticket/2273
		$this->assertFalse($this->entity->clearMetaData('impotent'));
		$this->assertEqual($this->entity->important, 'indeed!');
		
		// get rid of one metadata
		$this->assertEqual($this->entity->important, 'indeed!');
		$this->assertTrue($this->entity->clearMetaData('important'));
		$this->assertEqual($this->entity->important, '');
		
		// get rid of all metadata
		$this->assertTrue($this->entity->clearMetaData());
		$this->assertEqual($this->entity->less_important, '');
		
		// clean up database
		$this->assertTrue($this->entity->delete());
	}

	public function testElggEntityExportables() {
		$exportables = array(
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'site_guid'
		);

		$this->assertIdentical($exportables, $this->entity->getExportableValues());
	}

	protected function save_entity($type='site')
	{
		$this->entity->type = $type;
		$this->assertNotEqual($this->entity->save(), 0);
	}
}

// ElggEntity is an abstract class with no abstact methods.
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
}
