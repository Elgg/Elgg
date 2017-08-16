<?php

namespace Elgg;

/**
 * @group IntegrationTests
 */
class IntegrationTestCaseIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	private $entity;

	public function up() {
		$this->entity = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
		]);
	}

	public function down() {
		$ia = elgg_set_ignore_access();
		$this->entity->delete();
		elgg_set_ignore_access($ia);
	}

	public function testCanLoadSeededEntity() {
		$count = elgg_get_entities([
			'guids' => $this->entity->guid,
			'count' => true,
		]);

		$this->assertTrue($count === 1);
	}

}