<?php
/**
 * Elgg Test metadata API
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreMetadataAPITest extends \ElggCoreUnitTest {

	/**
	 * @var ElggObject
	 */
	protected $object;

	public function up() {
		$this->object = new \ElggObject();
		$this->object->subtype = $this->getRandomSubtype();
	}

	public function down() {
		unset($this->object);
	}


	public function testElggGetEntitiesFromMetadata() {
		
		$this->object->title = 'Meta Unit Test';
		$this->object->save();

		// create_metadata returns id of metadata on success
		$this->assertNotEqual(false, _elgg_services()->metadataTable->create($this->object->guid, 'metaUnitTest', 'tested'));

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
		$this->object->tested = ['tested1', 'tested2'];

		$count = (int)elgg_get_metadata(array(
			'metadata_names' => array('tested'),
			'guid' => $guid,
			'count' => true,
		));

		$this->assertIdentical($count, 2);

		$this->object->delete();
	}

	public function testElggDeleteMetadata() {
		$e = new \ElggObject();
		$e->subtype = $this->getRandomSubtype();
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

	// Make sure metadata with multiple values is correctly deleted when re-written
	// by another user
	// https://github.com/elgg/elgg/issues/2776
	public function test_elgg_metadata_multiple_values() {
		$u1 = $this->createUser();

		$u2 = $this->createUser();

		$obj = $this->createObject([
			'owner_guid' => $u1->guid,
			'container_guid' => $u1->guid,
		]);

		elgg_delete_metadata([
			'limit' => 0,
			'guids' => $obj->guid,
		]);

		$md_values = array(
			'one',
			'two',
			'three'
		);

		// need to fake different logins.
		// good times without mocking.
		$original_user = $this->replaceSession($u1);
		
		$ia = elgg_set_ignore_access(false);

		// add metadata as one user
		$obj->test = $md_values;

		// check only these md exists
		$db_prefix = _elgg_config()->dbprefix;
		$q = "SELECT * FROM {$db_prefix}metadata WHERE entity_guid = $obj->guid";
		$data = get_data($q);

		$this->assertEqual(count($md_values), count($data));
		foreach ($data as $md_row) {
			$md = elgg_get_metadata_from_id($md_row->id);
			$this->assertTrue(in_array($md->value, $md_values));
			$this->assertEqual('test', $md->name);
		}

		// add md w/ same name as a different user
		$this->replaceSession($u2);
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

		elgg_set_ignore_access($ia);

		$this->replaceSession($original_user);

		$obj->delete();
		$u1->delete();
		$u2->delete();
	}

	public function testDefaultOrderedById() {
		$ia = elgg_set_ignore_access(true);

		$obj = new ElggObject();
		$obj->subtype = $this->getRandomSubtype();
		$obj->owner_guid = elgg_get_site_entity()->guid;
		$obj->container_guid = elgg_get_site_entity()->guid;
		$obj->access_id = ACCESS_PUBLIC;
		$obj->save();

		$obj->test_md = [1, 2, 3];

		$time = time();
		$prefix = _elgg_services()->db->prefix;

		// all times the same
		$mds = elgg_get_metadata([
			'guid' => $obj->guid,
			'metadata_names' => 'test_md',
			'order_by' => 'n_table.id ASC',
		]);
		
		foreach ($mds as $md) {
			update_data("
				UPDATE {$prefix}metadata
				SET time_created = " . ($time) . "
				WHERE id = {$md->id}
			");
		}

		// with the same time_created expecting row order by ID
		$mds = elgg_get_metadata([
			'guid' => $obj->guid,
			'metadata_names' => 'test_md',
		]);
		$md_values = array_map(function (ElggMetadata $md) {
			return (int)$md->value;
		}, $mds);
		$this->assertEqual($md_values, [1, 2, 3]);

		// ignore access bypasses the MD cache, so we try it both ways
		elgg_set_ignore_access(false);
		_elgg_services()->metadataCache->clear($obj->guid);
		$md_values = $obj->test_md;
		$this->assertEqual($md_values, [1, 2, 3]);

		elgg_set_ignore_access(true);
		_elgg_services()->metadataCache->clear($obj->guid);
		$md_values = $obj->test_md;
		$this->assertEqual($md_values, [1, 2, 3]);

		$obj->delete();
		elgg_set_ignore_access($ia);
	}
}
