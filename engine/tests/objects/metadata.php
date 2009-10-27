<?php
/**
 * Elgg Test ElggMetadata
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreMetadataTest extends ElggCoreUnitTest {
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

	public function testGetEntitiesFromMetadata() {
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		$METASTRINGS_CACHE = $METASTRINGS_DEADNAME_CACHE = array();

		$this->object->title = 'Meta Unit Test';
		$this->object->save();
		$this->create_metastring('metaUnitTest');
		$this->create_metastring('tested');

		$this->assertTrue(create_metadata($this->object->guid, 'metaUnitTest', 'tested'));

		// check value with improper case
		$this->assertFalse(get_entities_from_metadata('metaUnitTest', 'Tested', '', '', 0, 10, 0, '', 0, FALSE, TRUE));

		// compare forced case with ignored case
		$case_true = get_entities_from_metadata('metaUnitTest', 'tested', '', '', 0, 10, 0, '', 0, FALSE, TRUE);
		$this->assertIsA($case_true, 'array');

		$case_false = get_entities_from_metadata('metaUnitTest', 'Tested', '', '', 0, 10, 0, '', 0, FALSE, FALSE);
		$this->assertIsA($case_false, 'array');

		$this->assertIdentical($case_true, $case_false);

		// check entity list
		//$this->dump(list_entities_from_metadata('metaUnitTest', 'Tested', '', '', 0, 10, TRUE, TRUE, TRUE, FALSE));

		// clean up
		$this->delete_metastrings();
		$this->object->delete();
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
