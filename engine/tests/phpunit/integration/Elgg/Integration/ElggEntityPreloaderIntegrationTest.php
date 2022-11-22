<?php

namespace Elgg\Integration;

use Elgg\EntityPreloader;
use Elgg\Helpers\MockEntityPreloader20140623;

/**
 * @group IntegrationTests
 */
class ElggEntityPreloaderIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var EntityPreloader
	 */
	protected $realPreloader;

	/**
	 * @var MockEntityPreloader20140623
	 */
	protected $mockPreloader;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		elgg()->session_manager->setLoggedInUser($this->user);
		
		$this->realPreloader = _elgg_services()->entityPreloader;
		$this->mockPreloader = new MockEntityPreloader20140623(_elgg_services()->entityTable);
		
		_elgg_services()->set('entityPreloader', $this->mockPreloader);
	}

	public function down() {
		_elgg_services()->set('entityPreloader', $this->realPreloader);
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
		$this->assertCount(3, $this->mockPreloader->preloaded);
	}

	public function testCanPreloadAnnotationOwners() {
		$object = $this->createObject();
		$object->annotate('test', 1);
		$object->annotate('test', 2);
		$object->annotate('test', 3);

		$options = [
			'types' => 'object',
			'limit' => 3,
		];

		$annotations = elgg_get_annotations($options);
		$this->assertCount(3, $annotations);

		$this->assertNull($this->mockPreloader->preloaded);

		$options['preload_owners'] = true;
		elgg_get_annotations($options);
		$this->assertCount(3, $this->mockPreloader->preloaded);
	}
}
