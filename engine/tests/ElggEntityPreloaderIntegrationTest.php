<?php

class ElggEntityPreloaderIntegrationTest extends ElggCoreUnitTest {

	protected $realPreloader;

	/**
	 * @var MockEntityPreloader20140623
	 */
	protected $mockPreloader;

	public function setUp() {
		$this->realPreloader = _elgg_services()->entityPreloader;

		$this->mockPreloader = new MockEntityPreloader20140623();
		_elgg_services()->setValue('entityPreloader', $this->mockPreloader);
	}

	public function tearDown() {
		_elgg_services()->setValue('entityPreloader', $this->realPreloader);
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

	public function preload($objects, array $guid_properties) {
		$this->preloaded = $objects;
	}
}
