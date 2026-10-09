<?php

class ElggUpgradeUnitTest extends \Elgg\UnitTestCase {

	protected ?\ElggUpgrade $obj = null;

	public function up() {
		$this->obj = new \ElggUpgrade();
	}

	public function mock_egefps_with_entities() {
		return array(new \stdClass());
	}

	public function testDefaultAttrs() {
		$site = elgg_get_site_entity();
		
		$this->assertSame('elgg_upgrade', $this->obj->subtype);
		$this->assertSame($site->guid, $this->obj->container_guid);
		$this->assertSame($site->guid, $this->obj->owner_guid);
		$this->assertFalse($this->obj->is_completed);
		$this->assertSame(0, $this->obj->offset);
	}

	public function testCanInstantiateBatchRunner() {
		_elgg_services()->logger->disable();

		$this->obj->class = '\InvalidClass';
		$this->assertFalse($this->obj->getBatch());

		$this->obj->class = \Elgg\Helpers\Upgrade\InvalidBatch::class;
		$this->assertFalse($this->obj->getBatch());

		$this->obj->class = \Elgg\Helpers\Upgrade\TestBatch::class;
		$this->assertInstanceOf(\Elgg\Helpers\Upgrade\TestBatch::class, $this->obj->getBatch());
	}
	
	public function testSetCompleted() {
		$upgrade = new \ElggUpgrade();
		
		$upgrade->setCompleted();
		
		$this->assertTrue($upgrade->isCompleted());
		$this->assertNotEmpty($upgrade->is_completed);
		
		$this->assertNotEmpty($upgrade->getCompletedTime());
		$this->assertNotEmpty($upgrade->completed_time);
		
		$this->assertNotEmpty($upgrade->getStartTime());
		$this->assertNotEmpty($upgrade->start_time);
	}
	
	public function testSetStarttime() {
		$upgrade = new \ElggUpgrade();
		
		$upgrade->setStartTime();
		
		$started = $upgrade->getStartTime();
		$this->assertNotEmpty($started);
		$this->assertEquals($started, $upgrade->start_time);
		
		// try to override the start time, this is not allowed
		$upgrade->setStartTime($started + 3600);
		$override = $upgrade->getStartTime();
		$this->assertEquals($started, $override);
		$this->assertEquals($started, $upgrade->start_time);
	}
	
	public function testReset() {
		$upgrade = new \ElggUpgrade();
		
		$upgrade->is_completed = true;
		$upgrade->completed_time = time();
		$upgrade->processed = 100;
		$upgrade->offset = 20;
		$upgrade->start_time = time() - 100;
		
		$upgrade->reset();
		
		$this->assertFalse($upgrade->is_completed);
		$this->assertSame(0, $upgrade->offset);
		
		$this->assertNull($upgrade->completed_time);
		$this->assertNull($upgrade->processed);
		$this->assertNull($upgrade->start_time);
	}
}
