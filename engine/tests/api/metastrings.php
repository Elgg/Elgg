<?php
/**
 * Elgg Metastrings test
 *
 * @package Elgg.Core
 * @subpackage Metastrings.Test
 */
class ElggCoreMetastringsTest extends ElggCoreUnitTest {

	public $metastringTypes = array('metadata', 'annotations');

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();

		$this->metastrings = array();
		$this->object = new ElggObject();
		$this->object->save();
	}

	public function createAnnotations($max = 1) {
		$annotations = array();
		for ($i=0; $i<$max; $i++) {
			$name = 'test_annotation_name' . rand();
			$value = 'test_annotation_value' . rand();
			$id = create_annotation($this->object->guid, $name, $value);
			$annotations[] = $id;
		}

		return $annotations;
	}

	public function createMetadata($max = 1) {
		$metadata = array();
		for ($i=0; $i<$max; $i++) {
			$name = 'test_metadata_name' . rand();
			$value = 'test_metadata_value' . rand();
			$id = create_metadata($this->object->guid, $name, $value);
			$metadata[] = $id;
		}

		return $metadata;
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		$this->object->delete();

		parent::__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testDeleteByID() {
		$db_prefix = elgg_get_config('dbprefix');
		$annotations = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$table = $db_prefix . $type;
			$q = "SELECT * FROM $table WHERE id = $id";
			$test = get_data($q);

			$this->assertEqual($test[0]->id, $id);
			$this->assertTrue(elgg_delete_metastring_based_object_by_id($id, $type));
			$this->assertFalse(get_data($q));
		}
	}

	public function testGetMetastringObjectByID() {
		$db_prefix = elgg_get_config('dbprefix');
		$annotations = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$test = elgg_get_metastring_based_object_by_id($id, $type);

			$this->assertEqual($id, $test->id);
		}
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testEnableDisableByID() {
		$db_prefix = elgg_get_config('dbprefix');
		$annotations = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$table = $db_prefix . $type;
			$q = "SELECT * FROM $table WHERE id = $id";
			$test = get_data($q);

			// disable
			$this->assertEqual($test[0]->enabled, 'yes');
			$this->assertTrue(elgg_set_metastring_based_object_enabled_by_id($id, 'no', $type));

			$test = get_data($q);
			$this->assertEqual($test[0]->enabled, 'no');

			// enable
			$ashe = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			flush();
			$this->assertTrue(elgg_set_metastring_based_object_enabled_by_id($id, 'yes', $type));

			$test = get_data($q);
			$this->assertEqual($test[0]->enabled, 'yes');

			access_show_hidden_entities($ashe);
		}
	}


}
