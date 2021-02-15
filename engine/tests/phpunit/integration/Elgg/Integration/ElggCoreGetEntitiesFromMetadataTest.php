<?php

namespace Elgg\Integration;

use ElggObject;

/**
 * Test elgg_get_entities() with metadata queries
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityMetadata
 */
class ElggCoreGetEntitiesFromMetadataTest extends ElggCoreGetEntitiesBaseTest {

	public function testElggApiGettersEntityMetadataNameValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($e->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
		}

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataNameValidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_names = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = [];

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(2, $entities);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $e_guids));
			$entity->delete();
		}
	}

	public function testElggApiGettersEntityMetadataNameInvalidSingle() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_name = 'test_metadata_name_' . rand();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_invalid_name,
		]);

		$this->assertEquals([], $entities);

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataNameInvalidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_names = [];
		$md_invalid_names[] = 'test_metadata_name_' . rand();
		$md_invalid_names[] = 'test_metadata_name_' . rand();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_invalid_names,
		]);

		$this->assertEquals([], $entities);

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataNameMixedMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_names = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = [];

		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->guid;

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid name.
		$md_names[] = 'test_metadata_name_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($entity->guid, $valid->guid);
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// values
	public function testElggApiGettersEntityMetadataValueValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_value,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($e->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
		}

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataValueValidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_values = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = [];

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(2, $entities);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $e_guids));
			$entity->delete();
		}
	}

	public function testElggApiGettersEntityMetadataValueInvalidSingle() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_value = 'test_metadata_value_' . rand();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_invalid_value,
		]);

		$this->assertEquals([], $entities);

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataValueInvalidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_values = [];
		$md_invalid_values[] = 'test_metadata_value_' . rand();
		$md_invalid_values[] = 'test_metadata_value_' . rand();

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_invalid_values,
		]);

		$this->assertEquals([], $entities);

		$e->delete();
	}

	public function testElggApiGettersEntityMetadataValueMixedMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_values = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = [];

		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->guid;

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid value.
		$md_values[] = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values,
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($valid->guid, $entity->guid);
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// name_value_pairs
	public function testElggApiGettersEntityMetadataNVPValidNValidVEquals() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value,
				],
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($valid->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPValidNValidVEqualsTriple() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$md_name3 = 'test_metadata_name_' . rand();
		$md_value3 = 'test_metadata_value_' . rand();

		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->save();
		$guids[] = $valid->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$invalid_md_name2 = 'test_metadata_name_' . rand();
		$invalid_md_name3 = 'test_metadata_name_' . rand();
		
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->$invalid_md_name2 = $md_value2;
		$e->$invalid_md_name3 = $md_value3;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value,
				],
				[
					'name' => $md_name2,
					'value' => $md_value2,
				],
				[
					'name' => $md_name3,
					'value' => $md_value3,
				],
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($valid->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPValidNValidVEqualsDouble() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->save();
		$guids[] = $valid->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$invalid_md_name2 = 'test_metadata_name_' . rand();
		
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->$invalid_md_name2 = $md_value2;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value,
				],
				[
					'name' => $md_name2,
					'value' => $md_value2,
				],
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($valid->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function xtestElggApiGettersEntityMetadataNVPValidNValidVEqualsStupid() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$md_name3 = 'test_metadata_name_' . rand();
		$md_value3 = 'test_metadata_value_' . rand();

		$md_name4 = 'test_metadata_name_' . rand();
		$md_value4 = 'test_metadata_value_' . rand();

		$md_name5 = 'test_metadata_name_' . rand();
		$md_value5 = 'test_metadata_value_' . rand();

		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->$md_name4 = $md_value4;
		$valid->$md_name5 = $md_value5;
		$valid->save();
		$guids[] = $valid->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->$md_name2 = $md_value2;
		$e->$md_name3 = $md_value3;
		$e->$md_name4 = $md_value4;
		$e->$md_name5 = $md_value5;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->$md_name4 = $invalid_md_value;
		$e->$md_name5 = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value,
				],
				[
					'name' => $md_name2,
					'value' => $md_value2,
				],
				[
					'name' => $md_name3,
					'value' => $md_value3,
				],
				[
					'name' => $md_name4,
					'value' => $md_value4,
				],
				[
					'name' => $md_name5,
					'value' => $md_value5,
				],
			]
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertEquals($valid->guid, $entity->guid);
			$this->assertEquals($md_value, $entity->$md_name);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	/**
	 * Name value pair with valid name and invalid value
	 */
	public function testElggApiGettersEntityMetadataNVPValidNInvalidV() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => 'test_metadata_value_' . rand(),
				],
			],
		]);

		$this->assertEquals([], $entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	/**
	 * Name value pair with invalid name and valid value
	 */
	public function testElggApiGettersEntityMetadataNVPInvalidNValidV() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => 'test_metadata_name_' . rand(),
					'value' => $md_value,
				],
			],
		]);

		$this->assertEquals([], $entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPValidNValidVOperandIn() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$md_valid_values = "'$md_value', '$md_value2'";

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN',
				],
				[
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN',
				],
			],
			'metadata_name_value_pairs_operator' => 'OR',
		]);

		$this->assertIsArray($entities);
		$this->assertCount(2, $entities);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPValidNValidVPlural() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->guid;

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->setSubtype($subtype);
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->guid;

		$md_valid_values = [
			$md_value,
			$md_value2,
		];

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN',
				],
				[
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN',
				],
			],
			'metadata_name_value_pairs_operator' => 'OR',
		]);

		$this->assertIsArray($entities);
		$this->assertCount(2, $entities);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPOrderByMDText() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 1;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 2;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 3;
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer',
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(3, $entities);

		$i = 1;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($i, $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityMetadataNVPOrderByMDString() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 'a';
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 'b';
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 'c';
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'text',
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(3, $entities);

		$alpha = [
			'a',
			'b',
			'c',
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($alpha[$i], $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// test getting by name sorting by value as integer
	public function testElggApiGettersEntityMetadataNOrderByMDInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer',
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(3, $entities);

		$num = [
			1,
			5,
			15,
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($num[$i], $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// test getting by name sorting by value as integer with defined values
	public function testElggApiGettersEntityMetadataNOrderByMDIntDefinedVals() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$num = [
			1,
			5,
			15,
		];

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'metadata_values' => $num,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		]);

		$this->assertIsArray($entities);
		$this->assertCount(3, $entities);

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($num[$i], $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// test getting by name_value_pairs sorting by value as integer
	// because string comparison '5' > '15'
	public function testElggApiGettersEntityMetadataNVPOrderByMDInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$num = [
			1,
			5,
			15,
		];

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => $num,
			],
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer',
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(3, $entities);

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($num[$i], $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// test getting by name sorting by value as integer with defined values
	public function testElggApiGettersEntityMetadataNVPGreaterThanInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$valid2 = new ElggObject();
		$valid2->setSubtype($subtype);
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid2->guid;

		$valid3 = new ElggObject();
		$valid3->setSubtype($subtype);
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid3->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => 4,
				'operand' => '>',
			],
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer',
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(2, $entities);

		$num = [
			5,
			15,
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($num[$i], $entity->$md_name);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// test getting from string value interpreted as numeric
	// see https://github.com/Elgg/Elgg/issues/7009
	public function testElggApiGettersEntityMetadataNVPInvalidDouble() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		$value = '052e866869';

		// our targets
		$valid = new ElggObject();
		$valid->setSubtype($subtype);
		$valid->$md_name = $value;
		$valid->save();
		$guids[] = $valid->guid;
		$valid_guids[] = $valid->guid;

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => $value,
			],
		]);

		$this->assertIsArray($entities);
		$this->assertCount(1, $entities);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $valid_guids));
			$this->assertEquals($value, $entity->$md_name);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}
}
