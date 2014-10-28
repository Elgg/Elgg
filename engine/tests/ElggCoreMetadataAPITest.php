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

		unset($this->object);
	}

	public function testGetMetastringById() {
		foreach (array('metaUnitTest', 'metaunittest', 'METAUNITTEST') as $string) {
			// since there is no guarantee that metastrings are garbage collected
			// between unit test runs, we delete before testing
			$this->delete_metastrings($string);
			$this->create_metastring($string);
		}

		// lookup metastring id
		$cs_ids = elgg_get_metastring_id('metaUnitTest', true);
		$this->assertEqual($cs_ids, $this->metastrings['metaUnitTest']);

		// lookup all metastrings, ignoring case
		$cs_ids = elgg_get_metastring_id('metaUnitTest', false);
		$this->assertEqual(count($cs_ids), 3);
		$this->assertEqual(count($cs_ids), count($this->metastrings));
		foreach ($cs_ids as $string )
		{
			$this->assertTrue(in_array($string, $this->metastrings));
		}
	}

	public function testElggGetEntitiesFromMetadata() {
		global $CONFIG, $METASTRINGS_CACHE;
		$METASTRINGS_CACHE = array();

		$this->object->title = 'Meta Unit Test';
		$this->object->save();
		$this->create_metastring('metaUnitTest');
		$this->create_metastring('tested');

		// create_metadata returns id of metadata on success
		$this->assertNotEqual(false, create_metadata($this->object->guid, 'metaUnitTest', 'tested'));

		// check value with improper case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => true);
		$this->assertIdentical(array(), elgg_get_entities_from_metadata($options));

		// compare forced case with ignored case
		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'tested', 'limit' => 10, 'metadata_case_sensitive' => true);
		$case_true = elgg_get_entities_from_metadata($options);
		$this->assertIsA($case_true, 'array');

		$options = array('metadata_names' => 'metaUnitTest', 'metadata_values' => 'Tested', 'limit' => 10, 'metadata_case_sensitive' => false);
		$case_false = elgg_get_entities_from_metadata($options);
		$this->assertIsA($case_false, 'array');

		$this->assertIdentical($case_true, $case_false);

		// clean up
		$this->object->delete();
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

		for ($i = 0; $i < 30; $i++) {
			$name = "test_metadata$i";
			$e->$name = rand(0, 10000);
		}

		$options = array(
			'guid' => $e->getGUID(),
			'limit' => 0,
		);

		$md = elgg_get_metadata($options);
		$this->assertIdentical(30, count($md));

		$this->assertTrue(elgg_delete_metadata($options));

		$md = elgg_get_metadata($options);
		$this->assertTrue(empty($md));

		$e->delete();
	}

	/**
	 * https://github.com/Elgg/Elgg/issues/4867
	 */
	public function testElggGetEntityMetadataWhereSqlWithFalseValue() {
		$pair = array('name' => 'test' , 'value' => false);
		$result = _elgg_get_entity_metadata_where_sql('e', 'metadata', null, null, $pair);
		$where = preg_replace( '/\s+/', ' ', $result['wheres'][0]);
		$this->assertTrue(strpos($where, "msn1.string = 'test' AND BINARY msv1.string = 0") > 0);

		$result = _elgg_get_entity_metadata_where_sql('e', 'metadata', array('test'), array(false));
		$where = preg_replace( '/\s+/', ' ', $result['wheres'][0]);
		$this->assertTrue(strpos($where, "msn.string IN ('test')) AND ( BINARY msv.string IN ('0')"));
	}

	// Make sure metadata with multiple values is correctly deleted when re-written
	// by another user
	// https://github.com/elgg/elgg/issues/2776
	public function test_elgg_metadata_multiple_values() {
		$u1 = new ElggUser();
		$u1->username = rand();
		$u1->save();

		$u2 = new ElggUser();
		$u2->username = rand();
		$u2->save();

		$obj = new ElggObject();
		$obj->owner_guid = $u1->guid;
		$obj->container_guid = $u1->guid;
		$obj->access_id = ACCESS_PUBLIC;
		$obj->save();

		$md_values = array(
			'one',
			'two',
			'three'
		);

		// need to fake different logins.
		// good times without mocking.
		$original_user = elgg_get_logged_in_user_entity();
		$_SESSION['user'] = $u1;

		elgg_set_ignore_access(false);

		// add metadata as one user
		$obj->test = $md_values;

		// check only these md exists
		$db_prefix = elgg_get_config('dbprefix');
		$q = "SELECT * FROM {$db_prefix}metadata WHERE entity_guid = $obj->guid";
		$data = get_data($q);

		$this->assertEqual(count($md_values), count($data));
		foreach ($data as $md_row) {
			$md = elgg_get_metadata_from_id($md_row->id);
			$this->assertTrue(in_array($md->value, $md_values));
			$this->assertEqual('test', $md->name);
		}

		// add md w/ same name as a different user
		$_SESSION['user'] = $u2;
		$md_values2 = array(
			'four',
			'five',
			'six',
			'seven'
		);

		$obj->test = $md_values2;

		$q = "SELECT * FROM {$db_prefix}metadata WHERE entity_guid = $obj->guid";
		$data = get_data($q);

		$this->assertEqual(count($md_values2), count($data));
		foreach ($data as $md_row) {
			$md = elgg_get_metadata_from_id($md_row->id);
			$this->assertTrue(in_array($md->value, $md_values2));
			$this->assertEqual('test', $md->name);
		}

		$_SESSION['user'] = $original_user;

		$obj->delete();
		$u1->delete();
		$u2->delete();
	}

	protected function delete_metastrings($string) {
		global $CONFIG, $METASTRINGS_CACHE;
		$METASTRINGS_CACHE = array();

		$string = sanitise_string($string);
		mysql_query("DELETE FROM {$CONFIG->dbprefix}metastrings WHERE string = BINARY '$string'");
	}

	protected function create_metastring($string) {
		global $CONFIG, $METASTRINGS_CACHE;
		$METASTRINGS_CACHE = array();

		$string = sanitise_string($string);
		mysql_query("INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ('$string')");
		$this->metastrings[$string] = mysql_insert_id();
	}
}
