<?php

namespace Elgg\Integration;

use ElggObject;

/**
 * Test elgg_get_entities_from_metadata()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityMetadata
 */
class ElggCoreGetEntitiesFromMetadataTest extends ElggCoreGetEntitiesBaseTest {

	//names
	function testElggApiGettersEntityMetadataNameValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $e->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
		}

		$e->delete();
	}

	function testElggApiGettersEntityMetadataNameValidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_names = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = [];

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $e_guids));
			$entity->delete();
		}
	}

	function testElggApiGettersEntityMetadataNameInvalidSingle() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_name = 'test_metadata_name_' . rand();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_invalid_name
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		$e->delete();
	}

	function testElggApiGettersEntityMetadataNameInvalidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_names = [];
		$md_invalid_names[] = 'test_metadata_name_' . rand();
		$md_invalid_names[] = 'test_metadata_name_' . rand();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_invalid_names
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		$e->delete();
	}


	function testElggApiGettersEntityMetadataNameMixedMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_names = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = [];

		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid name.
		$md_names[] = 'test_metadata_name_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	// values
	function testElggApiGettersEntityMetadataValueValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_value
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $e->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
		}

		$e->delete();
	}

	function testElggApiGettersEntityMetadataValueValidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_values = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = [];

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $e_guids));
			$entity->delete();
		}
	}

	function testElggApiGettersEntityMetadataValueInvalidSingle() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_value = 'test_metadata_value_' . rand();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_invalid_value
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		$e->delete();
	}

	function testElggApiGettersEntityMetadataValueInvalidMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_values = [];
		$md_invalid_values[] = 'test_metadata_value_' . rand();
		$md_invalid_values[] = 'test_metadata_value_' . rand();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_invalid_values
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		$e->delete();
	}


	function testElggApiGettersEntityMetadataValueMixedMultiple() {
		$subtype = $this->getRandomSubtype();

		$md_values = [];

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = [];

		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid value.
		$md_values[] = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	// name_value_pairs


	function testElggApiGettersEntityMetadataNVPValidNValidVEquals() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value
				]
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVEqualsTriple() {
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
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$invalid_md_name2 = 'test_metadata_name_' . rand();
		$invalid_md_name3 = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->$invalid_md_name2 = $md_value2;
		$e->$invalid_md_name3 = $md_value3;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value
				],
				[
					'name' => $md_name2,
					'value' => $md_value2
				],
				[
					'name' => $md_name3,
					'value' => $md_value3
				]
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVEqualsDouble() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$guids = [];

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$invalid_md_name2 = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->$invalid_md_name2 = $md_value2;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value
				],
				[
					'name' => $md_name2,
					'value' => $md_value2
				]
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	// this keeps locking up my database...
	function xtestElggApiGettersEntityMetadataNVPValidNValidVEqualsStupid() {
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
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->$md_name4 = $md_value4;
		$valid->$md_name5 = $md_value5;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->$md_name2 = $md_value2;
		$e->$md_name3 = $md_value3;
		$e->$md_name4 = $md_value4;
		$e->$md_name5 = $md_value5;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->$md_name4 = $invalid_md_value;
		$e->$md_name5 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_value
				],
				[
					'name' => $md_name2,
					'value' => $md_value2
				],
				[
					'name' => $md_name3,
					'value' => $md_value3
				],
				[
					'name' => $md_name4,
					'value' => $md_value4
				],
				[
					'name' => $md_name5,
					'value' => $md_value5
				],
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
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
	function testElggApiGettersEntityMetadataNVPValidNInvalidV() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => 'test_metadata_value_' . rand()
				]
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	/**
	 * Name value pair with invalid name and valid value
	 */
	function testElggApiGettersEntityMetadataNVPInvalidNValidV() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => 'test_metadata_name_' . rand(),
					'value' => $md_value
				]
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIdentical([], $entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	function testElggApiGettersEntityMetadataNVPValidNValidVOperandIn() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_valid_values = "'$md_value', '$md_value2'";

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN'
				],
				[
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN'
				],
			],
			'metadata_name_value_pairs_operator' => 'OR'
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVPlural() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$invalid_md_name = $md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_valid_values = [
			$md_value,
			$md_value2
		];

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				[
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN'
				],
				[
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN'
				],
			],
			'metadata_name_value_pairs_operator' => 'OR'
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPOrderByMDText() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 1;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 2;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 3;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			//'metadata_name' => $md_name,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$i = 1;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $i);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPOrderByMDString() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 'a';
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 'b';
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 'c';
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'text'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$alpha = [
			'a',
			'b',
			'c'
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $alpha[$i]);
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
	function testElggApiGettersEntityMetadataNOrderByMDInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$num = [
			1,
			5,
			15
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $num[$i]);
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
	function testElggApiGettersEntityMetadataNOrderByMDIntDefinedVals() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$num = [
			1,
			5,
			15
		];

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'metadata_values' => $num,
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $num[$i]);
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
	function testElggApiGettersEntityMetadataNVPOrderByMDInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$num = [
			1,
			5,
			15
		];

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => $num
			],
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $num[$i]);
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

	/**
	 * @group Failing
	 */
	function testElggApiGettersEntityMetadataNVPGreaterThanInt() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = 5;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 1;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 15;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => 4,
				'operand' => '>'
			],
			'order_by_metadata' => [
				'name' => $md_name,
				'as' => 'integer'
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 2);

		$num = [
			5,
			15
		];

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $num[$i]);
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
	function testElggApiGettersEntityMetadataNVPInvalidDouble() {
		$subtype = $this->getRandomSubtype();

		$md_name = 'test_metadata_name_' . rand();
		$guids = [];
		$valid_guids = [];

		$value = '052e866869';

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => [
				'name' => $md_name,
				'value' => $value
			]
		];

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsA($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}
}
