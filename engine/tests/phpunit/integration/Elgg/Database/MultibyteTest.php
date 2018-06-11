<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

/**
 * @group Database
 * @group Multibyte
 */
class MultibyteTest extends IntegrationTestCase {

	public function up() {
		_elgg_services()->session->setIgnoreAccess(true);
	}

	public function down() {
		_elgg_services()->session->setIgnoreAccess(false);
	}

	public function testCanUseMultibyteCharsInMetadata() {

		$object = $this->createObject();

		$title = "😀 Grinning Face";

		$object->title = $title;

		$object->save();

		elgg_flush_caches();

		$object = get_entity($object->guid);

		$this->assertRegExp('/\\x{1f600}/u', $object->title);
		$this->assertEquals($title, $object->title);
	}

	public function testCanFindMetadataWithMultibyteChars() {

		$object = $this->createObject();

		$title = "😀 Grinning Face";

		$object->title = $title;

		$object->save();

		elgg_flush_caches();

		$entities = elgg_get_entities([
			'guids' => $object->guid,
			'metadata_name_value_pairs' => [
				[
					'name' => 'title',
					'value' => "%😀%",
					'operand' => 'LIKE',
				],
			],
		]);

		$object = array_shift($entities);

		$this->assertRegExp('/\\x{1f600}/u', $object->title);
		$this->assertEquals($title, $object->title);
	}

}