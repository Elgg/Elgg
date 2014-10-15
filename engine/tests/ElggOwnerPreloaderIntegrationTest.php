<?php

class ElggOwnerPreloaderIntegrationTest extends ElggCoreUnitTest {

	protected $realPreloader;

	/**
	 * @var MockEntityPreloader20140623
	 */
	protected $mockPreloader;

	public function setUp() {
		$this->realPreloader = _elgg_services()->ownerPreloader;

		$this->mockPreloader = new MockEntityPreloader20140623(array('owner_guid'));
		_elgg_services()->setValue('ownerPreloader', $this->mockPreloader);
	}

	public function tearDown() {
		_elgg_services()->setValue('ownerPreloader', $this->realPreloader);
	}

	public function testEGECanUsePreloader() {
		$options = array(
			'limit' => 3,
		);

		elgg_get_entities($options);
		$this->assertNull($this->mockPreloader->preloaded);

		$options['preload_owners'] = true;
		elgg_get_entities($options);
		$this->assertEqual(3, count($this->mockPreloader->preloaded));
	}

	public function testEGMCanUsePreloader() {
		$options = array(
			'limit' => 3,
		);

		elgg_get_metadata($options);
		$this->assertNull($this->mockPreloader->preloaded);

		$options['preload_owners'] = true;
		elgg_get_metadata($options);
		$this->assertEqual(3, count($this->mockPreloader->preloaded));
	}
}

class MockEntityPreloader20140623 extends Elgg\EntityPreloader {
	public $preloaded;

	public function preload($objects) {
		$this->preloaded = $objects;
	}
}
