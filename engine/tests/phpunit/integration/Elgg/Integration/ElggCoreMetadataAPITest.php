<?php

namespace Elgg\Integration;

use Elgg\Database\Select;
use Elgg\IntegrationTestCase;
use ElggMetadata;
use ElggObject;

/**
 * Elgg Test metadata API
 *
 * @group IntegrationTests
 * @group Metadata
 */
class ElggCoreMetadataAPITest extends IntegrationTestCase {

	/**
	 * @var ElggObject
	 */
	protected $object;

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
		$this->object = new ElggObject();
		$this->object->setSubtype($this->getRandomSubtype());
	}

	public function down() {
		if ($this->object instanceof \ElggEntity) {
			$this->object->delete();
		}

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testElggGetEntitiesFromMetadata() {

		$this->object->title = 'Meta Unit Test';
		$this->object->save();

		// create_metadata returns id of metadata on success
		$this->object->setMetadata('metaUnitTest', 'tested');

		// check value with improper case
		$options = [
			'metadata_names' => 'metaUnitTest',
			'metadata_values' => 'Tested',
			'limit' => 10,
			'metadata_case_sensitive' => true
		];
		$this->assertEquals([], elgg_get_entities($options));

		// compare forced case with ignored case
		$options = [
			'metadata_names' => 'metaUnitTest',
			'metadata_values' => 'tested',
			'limit' => 10,
			'metadata_case_sensitive' => true
		];
		$case_true = elgg_get_entities($options);
		$this->assertIsArray($case_true);

		$options = [
			'metadata_names' => 'metaUnitTest',
			'metadata_values' => 'Tested',
			'limit' => 10,
			'metadata_case_sensitive' => false
		];
		$case_false = elgg_get_entities($options);
		$this->assertIsArray($case_false);

		$this->assertEquals($case_true, $case_false);

		// clean up
		$this->object->delete();
	}

	/**
	 * @dataProvider caseSensitivePairsProvider
	 */
	public function testElggGetEntitiesFromMetadataCaseSensitive($comparison, $value, $case_sensitive, $count) {

		$this->object->setSubtype($this->getRandomSubtype());
		$this->object->metadata = 'CaseSensitive';
		$this->object->save();

		$options = [
			'type' => 'object',
			'subtype' => $this->object->subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => 'metadata',
					'value' => $value,
					'operand' => $comparison,
					'case_sensitive' => $case_sensitive,
				],
			],
			'count' => true,
		];

		$result = elgg_get_entities($options);

		$this->assertEquals($count, $result);

		$this->object->delete();
	}

	public function caseSensitivePairsProvider() {
		return [
			['=', 'CaseSensitive', true, 1],
			['=', 'CaseSensitive', false, 1],
			['=', 'casesensitive', true, 0],
			['=', 'casesensitive', false, 1],

			['in', ['CaseSensitive', 123], true, 1],
			['in', ['CaseSensitive', 123], false, 1],
			['in', ['casesensitive', 123], true, 0],
			['in', ['casesensitive', 123], false, 1],

			['!=', 'CaseSensitive', true, 0],
			['!=', 'CaseSensitive', false, 0],
			['!=', 'casesensitive', true, 1],
			['!=', 'casesensitive', false, 0],

			['not in', ['CaseSensitive', 123], true, 0],
			['not in', ['CaseSensitive', 123], false, 0],
			['not in', ['casesensitive', 123], true, 1],
			['not in', ['casesensitive', 123], false, 0],

			['like', 'Case%', true, 1],
			['like', 'Case%', false, 1],
			['like', 'case%', true, 0],
			['like', 'case%', false, 1],
			['like', ['Case%', 123], true, 1],
			['like', ['Case%', 123], false, 1],
			['like', ['case%', 123], true, 0],
			['like', ['case%', 123], false, 1],

			['not like', 'Case%', true, 0],
			['not like', 'Case%', false, 0],
			['not like', 'case%', true, 1],
			['not like', 'case%', false, 0],
			['not like', ['Case%', 123], true, 0],
			['not like', ['Case%', 123], false, 0],
			['not like', ['case%', 123], true, 1],
			['not like', ['case%', 123], false, 0],

			['>', 'CaseSensitiv', true, 1],
			['>', 'CaseSensitiv', false, 1],
			['>', 'casesensitiv', true, 0],
			['>', 'casesensitiv', false, 1],

			['<', 'CaseSensitive1', true, 1],
			['<', 'CaseSensitive1', false, 1],
			['<', 'casesensitive1', true, 1],
			['<', 'casesensitive1', false, 1],
		];
	}

	/**
	 * @dataProvider booleanPairsProvider
	 */
	public function testElggGetEntitiesFromBooleanMetadata($value, $query, $type) {

		$this->object->setSubtype($this->getRandomSubtype());
		$this->object->metadata = $value;
		$this->object->save();

		$options = [
			'type' => 'object',
			'subtype' => $this->object->subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => 'metadata',
					'value' => $query,
					'operand' => '=',
					'type' => $type,
				]
			],
			'count' => true,
		];

		$result = elgg_get_entities($options);

		$this->assertEquals(1, $result);

		$this->object->delete();
	}

	public function booleanPairsProvider() {
		return [
			[true, true, null],
			[true, 1, null],
			[true, '1', ELGG_VALUE_INTEGER],
			[false, false, null],
			[false, 0, null],
			[false, '0', ELGG_VALUE_INTEGER],
			[1, true, null],
			[0, false, null],
		];
	}

	public function testElggGetMetadataCount() {
		$this->object->title = 'Meta Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		$this->object->tested = ['tested1', 'tested2'];

		$count = (int) elgg_get_metadata([
			'metadata_names' => ['tested'],
			'guid' => $guid,
			'count' => true,
		]);

		$this->assertEquals(2, $count);

		$this->object->delete();
	}

	public function testElggDeleteMetadata() {
		$e = new ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();

		for ($i = 0; $i < 30; $i++) {
			$name = "test_metadata$i";
			$e->$name = rand(0, 10000);
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => 0,
			'wheres' => [
				"n_table.name LIKE 'test_metadata%'",
			],
		];

		$md = elgg_get_metadata($options);
		$this->assertEquals(30, count($md));

		$this->assertTrue(elgg_delete_metadata($options));

		$md = elgg_get_metadata($options);
		$this->assertTrue(empty($md));

		$e->delete();
	}


	// Make sure metadata with multiple values is correctly deleted when re-written
	// by another user
	// https://github.com/elgg/elgg/issues/2776
	public function test_elgg_metadata_multiple_values() {
		$user1 = $this->createOne('user');

		$user2 = $this->createOne('user');

		$entity = new ElggObject();
		$entity->setSubtype($this->getRandomSubtype());
		$entity->owner_guid = $user1->guid;
		$entity->container_guid = $user1->guid;
		$entity->access_id = ACCESS_PUBLIC;
		$entity->save();

		// need to fake different logins.
		// good times without mocking.
		$original_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->setLoggedInUser($user1);

		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($entity, $user2) {
			$md_values = [
				'one',
				'two',
				'three'
			];
		
			// add metadata as one user
			$entity->test = $md_values;
	
			// check only these md exists
			$qb = Select::fromTable('metadata');
			$qb->select('*');
			$qb->where($qb->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_INTEGER))
				->andWhere($qb->compare('name', '=', 'test', ELGG_VALUE_STRING));
	
			$data = elgg()->db->getData($qb);
	
			$this->assertEquals(count($md_values), count($data));
			foreach ($data as $md_row) {
				$md = elgg_get_metadata_from_id($md_row->id);
				$this->assertTrue(in_array($md->value, $md_values));
				$this->assertEquals('test', $md->name);
			}
	
			// add md w/ same name as a different user
			_elgg_services()->session->setLoggedInUser($user2);
			$md_values2 = [
				'four',
				'five',
				'six',
				'seven'
			];
	
			$entity->test = $md_values2;
	
			$data = elgg()->db->getData($qb);
	
			$this->assertEquals(count($md_values2), count($data));
			foreach ($data as $md_row) {
				$md = elgg_get_metadata_from_id($md_row->id);
				$this->assertTrue(in_array($md->value, $md_values2));
				$this->assertEquals('test', $md->name);
			}
		});
		
		_elgg_services()->session->setLoggedInUser($original_user);

		$entity->delete();
		$user1->delete();
		$user2->delete();
	}

	public function testDefaultOrderedById() {
		$obj = null;
		$md_values = null;
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use (&$obj, &$md_values) {
			$obj = new ElggObject();
			$obj->setSubtype($this->getRandomSubtype());
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
				elgg()->db->updateData("
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
				return (int) $md->value;
			}, $mds);
			$this->assertEquals([1, 2, 3], $md_values);
		});
		
		// ignore access bypasses the MD cache, so we try it both ways
		elgg_call(ELGG_ENFORCE_ACCESS, function() use (&$obj, &$md_values) {
			_elgg_services()->metadataCache->clear($obj->guid);
			$md_values = $obj->test_md;
			$this->assertEquals([1, 2, 3], $md_values);
		});
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use (&$obj, &$md_values) {
			_elgg_services()->metadataCache->clear($obj->guid);
			$md_values = $obj->test_md;
			$this->assertEquals([1, 2, 3], $md_values);
	
			$obj->delete();
		});
	}

	public function testCanDeleteMetadataById() {

		$entity = $this->createObject([], [
			'foo' => 'bar',
			'bar' => 'baz',
		]);

		$mds = elgg_get_metadata([
			'guid' => $entity->guid,
			'metadata_names' => ['foo', 'bar'],
		]);

		foreach ($mds as $md) {
			$this->assertTrue(elgg_delete_metadata_by_id($md->id));
		}

		$this->assertNull($entity->foo);
		$this->assertNull($entity->bar);
	}
}
