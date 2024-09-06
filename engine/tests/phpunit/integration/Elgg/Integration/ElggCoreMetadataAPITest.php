<?php

namespace Elgg\Integration;

use Elgg\Database\MetadataTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\IntegrationTestCase;

class ElggCoreMetadataAPITest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	protected $object;

	public function up() {
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());
		
		// can not use createObject(). The tests rely on an unsaved entity
		$this->object = new \ElggObject();
		$this->object->setSubtype($this->getRandomSubtype());
	}

	public function down() {
		if ($this->object instanceof \ElggEntity) {
			$this->object->delete();
		}
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

	public static function caseSensitivePairsProvider() {
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

	public static function booleanPairsProvider() {
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
		$e = new \ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();

		for ($i = 0; $i < 30; $i++) {
			$name = "test_metadata{$i}";
			$e->$name = rand(0, 10000);
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => false,
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.name", 'like', 'test_metadata%', ELGG_VALUE_STRING);
				},
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
		$user1 = $this->createUser();
		$user2 = $this->createUser();

		$entity = $this->createObject([
			'owner_guid' => $user1->guid,
			'container_guid' => $user1->guid,
		]);

		// need to fake different logins.
		// good times without mocking.
		$original_user = _elgg_services()->session_manager->getLoggedInUser();
		_elgg_services()->session_manager->setLoggedInUser($user1);

		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($entity, $user2) {
			$md_values = [
				'one',
				'two',
				'three'
			];
		
			// add metadata as one user
			$entity->test = $md_values;
	
			// check only these md exists
			$qb = Select::fromTable(MetadataTable::TABLE_NAME);
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
			_elgg_services()->session_manager->setLoggedInUser($user2);
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
		
		_elgg_services()->session_manager->setLoggedInUser($original_user);
	}

	public function testDefaultOrderedById() {
		$obj = null;
		$md_values = null;
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use (&$obj, &$md_values) {
			$obj = new \ElggObject();
			$obj->setSubtype($this->getRandomSubtype());
			$obj->owner_guid = elgg_get_site_entity()->guid;
			$obj->container_guid = elgg_get_site_entity()->guid;
			$obj->access_id = ACCESS_PUBLIC;
			$obj->save();
	
			$obj->test_md = [1, 2, 3];
	
			$time = time();
	
			// all times the same
			$mds = elgg_get_metadata([
				'guid' => $obj->guid,
				'metadata_names' => 'test_md',
				'order_by' => 'n_table.id ASC',
			]);
	
			foreach ($mds as $md) {
				$update_metadata = Update::table(MetadataTable::TABLE_NAME);
				$update_metadata->set('time_created', $update_metadata->param($time, ELGG_VALUE_TIMESTAMP));
				$update_metadata->where($update_metadata->compare('id', '=', $md->id, ELGG_VALUE_ID));
				
				elgg()->db->updateData($update_metadata);
			}
	
			// with the same time_created expecting row order by ID
			$mds = elgg_get_metadata([
				'guid' => $obj->guid,
				'metadata_names' => 'test_md',
			]);
	
			$md_values = array_map(function (\ElggMetadata $md) {
				return (int) $md->value;
			}, $mds);
			$this->assertEquals([1, 2, 3], $md_values);
		});
		
		// ignore access bypasses the MD cache, so we try it both ways
		elgg_call(ELGG_ENFORCE_ACCESS, function() use (&$obj, &$md_values) {
			_elgg_services()->metadataCache->delete($obj->guid);
			$md_values = $obj->test_md;
			$this->assertEquals([1, 2, 3], $md_values);
		});
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use (&$obj, &$md_values) {
			_elgg_services()->metadataCache->delete($obj->guid);
			$md_values = $obj->test_md;
			$this->assertEquals([1, 2, 3], $md_values);
	
			$obj->delete();
		});
	}

	public function testCanDeleteMetadataByObject() {
		$entity = $this->createObject([
			'foo' => 'bar',
			'bar' => 'baz',
		]);

		$mds = elgg_get_metadata([
			'guid' => $entity->guid,
			'metadata_names' => ['foo', 'bar'],
		]);

		foreach ($mds as $md) {
			$this->assertTrue($md->delete());
		}

		$this->assertNull($entity->foo);
		$this->assertNull($entity->bar);
	}
}
