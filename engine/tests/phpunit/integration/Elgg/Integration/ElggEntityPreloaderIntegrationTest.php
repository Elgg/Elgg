<?php

namespace Elgg\Integration;

/**
 * @group IntegrationTests
 */
class ElggEntityPreloaderIntegrationTest extends \Elgg\LegacyIntegrationTestCase {

	protected $realPreloader;

	/**
	 * @var MockEntityPreloader20140623
	 */
	protected $mockPreloader;

	public function up() {
		$this->realPreloader = _elgg_services()->entityPreloader;

		$this->mockPreloader = new MockEntityPreloader20140623(_elgg_services()->entityTable);

		_elgg_services()->setValue('entityPreloader', $this->mockPreloader);
	}

	public function down() {
		_elgg_services()->setValue('entityPreloader', $this->realPreloader);
	}

	public function testCanPreloadEntityOwners() {
		$seeded = $this->createMany('object', 3);
		$options = [
			'types' => 'object',
			'limit' => 3,
		];

		$objects = elgg_get_entities($options);
		$this->assertEquals(3, count($objects));
		$this->assertNull($this->mockPreloader->preloaded);

		$options['preload_owners'] = true;
		elgg_get_entities($options);
		$this->assertEqual(3, count($this->mockPreloader->preloaded));

		foreach ($seeded as $object) {
			$object->delete();
		}
	}

	public function testCanPreloadAnnotationOwners() {
		$object = $this->createOne('object');
		$object->annotate('test', 1);
		$object->annotate('test', 2);
		$object->annotate('test', 3);

		$options = [
			'types' => 'object',
			'limit' => 3,
		];

		$annotations = elgg_get_annotations($options);
		$this->assertEquals(3, count($annotations));

		$this->assertNull($this->mockPreloader->preloaded);

		$options['preload_owners'] = true;
		elgg_get_annotations($options);
		$this->assertEqual(3, count($this->mockPreloader->preloaded));
	}
}

class MockEntityPreloader20140623 extends \Elgg\EntityPreloader {
	public $preloaded;

	public function preload($objects, array $guid_properties) {
		$this->preloaded = $objects;
	}
}
