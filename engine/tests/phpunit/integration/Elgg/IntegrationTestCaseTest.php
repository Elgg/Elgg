<?php

namespace Elgg;

/**
 * @group IntegrationTests
 */
class IntegrationTestCaseTest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	private $entity;

	public function up() {
		$this->entity = $this->createObject();
	}

	public function down() {
		$this->entity->delete();
	}

	public function testCanLoadSeededEntity() {
		$count = elgg_get_entities([
			'guids' => $this->entity->guid,
			'count' => true,
		]);

		$this->assertTrue($count === 1);
	}

}