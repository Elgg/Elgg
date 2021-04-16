<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\OrderByClause;
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

		$object = get_entity($object->guid);

		$this->assertMatchesRegularExpression('/\\x{1f600}/u', $object->title);
		$this->assertEquals($title, $object->title);
	}

	public function testCanFindMetadataWithMultibyteChars() {

		$object = $this->createObject();

		$title = "😀 Grinning Face";

		$object->title = $title;

		$object->save();

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

		$this->assertMatchesRegularExpression('/\\x{1f600}/u', $object->title);
		$this->assertEquals($title, $object->title);
	}

	public function testDatabaseCollactionAllowsSearchForMultibyteCharactersByExactMatch() {

		// @todo : does this work on all mysql versions?
		//$mysql_version = elgg()->db
		//->getConnection(DbConfig::READ_WRITE)
		//->getWrappedConnection()
		//->getAttribute(\PDO::ATTR_SERVER_VERSION);

		$grinning_face = $this->createObject([
			'title' => "😀 Grinning Face",
		]);

		$monkey_face = $this->createObject([
			'title' => "🐵 Monkey Face",
		]);

		$entities = elgg_get_entities([
			'guids' => [
				$grinning_face->guid,
				$monkey_face->guid,
			],
			'metadata_name_value_pairs' => [
				[
					'name' => 'title',
					'value' => "%😀%",
					'operand' => 'LIKE',
				],
			],
			'order_by' => new OrderByClause('e.guid', 'DESC'),
			'limit' => 0,
		]);

		$this->assertCount(1, $entities);

		$grinning_face = array_shift($entities);

		$this->assertMatchesRegularExpression('/\\x{1f600}/u', $grinning_face->title);
	}
}