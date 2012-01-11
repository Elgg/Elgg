<?php
/**
 * Elgg Test metadata API
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreMetadataAPITest extends ElggCoreUnitTest {
	protected $metastrings;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->metastrings = array();
		$this->object = new ElggObject();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();

		unset($this->object);
	}

	public function testGetMetastringById() {
		foreach (array('metaUnitTest', 'metaunittest', 'METAUNITTEST') as $string) {
			$this->create_metastring($string);
		}

		// lookup metastring id
		$cs_ids = get_metastring_id('metaUnitTest', TRUE);
		$this->assertEqual($cs_ids, $this->metastrings['metaUnitTest']);

		// lookup all metastrings, ignoring case
		$cs_ids = get_metastring_id('metaUnitTest', FALSE);
		$this->assertEqual(count($cs_ids), 3);
		$this->assertEqual(count($cs_ids), count($this->metastrings));
		foreach ($cs_ids as $string )
		{
			$this->assertTrue(in_array($string, $this->metastrings));
		}

		// clean up
		$this->delete_metastrings();
	}

	public function testElggGetEntitiesFromMetadata() {
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		$this->object->title = 'Meta Unit Test';
		$this->object->save();
		$this->create_metastring('metaUnitTest');
		$this->create_metastring('tested');

		// create_metadata returns id of metadata on success
		$this->assertNotEqual(false, create_metadata($this->object->guid, 'metaUnitTest', 'tested'));

		// check value with improper case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => TRUE);
		$this->assertIdentical(array(), elgg_get_entities_from_metadata($options));

		// compare forced case with ignored case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'tested', 'limit' => 10, 'metadata_case_sensitive' => TRUE);
		$case_true = elgg_get_entities_from_metadata($options);
		$this->assertIsA($case_true, 'array');

		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => FALSE);
		$case_false = elgg_get_entities_from_metadata($options);
		$this->assertIsA($case_false, 'array');

		$this->assertIdentical($case_true, $case_false);

		// clean up
		$this->object->delete();
		$this->delete_metastrings();
	}

	public function testElggGetMetadataCount() {
		$this->object->title = 'Meta Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		create_metadata($guid, 'tested', 'tested1', 'text', 0, ACCESS_PUBLIC, true);
		create_metadata($guid, 'tested', 'tested2', 'text', 0, ACCESS_PUBLIC, true);

		$count = (int)elgg_get_metadata(array(
			'metadata_names' => array('tested'),
			'guid' => $guid,
			'count' => true,
		));

		$this->assertIdentical($count, 2);

		$this->object->delete();
	}

	public function testElggDeleteMetadata() {
		$e = new ElggObject();
		$e->save();

		for ($i=0; $i<30; $i++) {
			$name = "test_metadata" . rand(0, 10000);
			$e->$name = rand(0, 10000);
		}

		$options = array(
			'guid' => $e->getGUID(),
			'limit' => 0
		);

		$md = elgg_get_metadata($options);
		$this->assertIdentical(30, count($md));

		$this->assertTrue(elgg_delete_metadata($options));

		$md = elgg_get_metadata($options);
		$this->assertTrue(empty($md));

		$e->delete();
	}


	protected function create_metastring($string) {
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		mysql_query("INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ('$string')");
		$this->metastrings[$string] = mysql_insert_id();
	}

	protected function delete_metastrings() {
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		$strings = implode(', ', $this->metastrings);
		mysql_query("DELETE FROM {$CONFIG->dbprefix}metastrings WHERE id IN ($strings)");
	}
}
