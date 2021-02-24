<?php

/**
 * @group UpgradeService
 * @group UnitTests
 */
class ElggUpgradeUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var ElggUpgrade
	 */
	protected $obj;

	public function up() {
		$this->obj = $this->getMockBuilder('\ElggUpgrade')
			->setMethods(null)
			->getMock();

	}

	public function down() {

	}

	public function mock_egefps_with_entities() {
		return array(new \stdClass());
	}

	public function mock_egefps_for_path($options) {
		if ($options['private_setting_value'] === 'test') {
			return array(new \stdClass());
		} else {
			return array();
		}
	}

	public function testDefaultAttrs() {
		$this->assertSame('elgg_upgrade', $this->obj->subtype);
		$this->assertSame(0, $this->obj->container_guid);
		$this->assertSame(0, $this->obj->owner_guid);
		$this->assertSame(null, $this->obj->is_completed);
	}

	public function testThrowsOnSaveWithoutClass() {
		$this->obj->description = 'Test';
		$this->obj->id = 'test';
		$this->obj->title = 'Test';
		
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('ElggUpgrade objects must have a value for the class property.');
		$this->obj->save();
	}

	public function testThrowsOnSaveWithoutTitle() {
		$this->obj->setClass('test');
		$this->obj->description = 'Test';
		$this->obj->id = 'test';
		
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('ElggUpgrade objects must have a value for the title property.');
		$this->obj->save();
	}

	public function testThrowsOnSaveWithoutDesc() {
		$this->obj->setClass('test');
		$this->obj->id = 'test';
		$this->obj->title = 'Test';
		
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('ElggUpgrade objects must have a value for the description property.');
		$this->obj->save();
	}

	public function testThrowsOnSaveWithoutId() {
		$this->obj->setClass('test');
		$this->obj->description = 'Test';
		$this->obj->title = 'Test';
		
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('ElggUpgrade objects must have a value for the id property.');
		$this->obj->save();
	}

	public function testCanInstantiateBatchRunner() {
		_elgg_services()->logger->disable();

		$this->obj->setClass('\InvalidClass');
		$this->assertFalse($this->obj->getBatch());

		$this->obj->setClass(\Elgg\Helpers\Upgrade\InvalidBatch::class);
		$this->assertFalse($this->obj->getBatch());

		$this->obj->setClass(\Elgg\Helpers\Upgrade\TestBatch::class);
		$this->assertInstanceOf(\Elgg\Helpers\Upgrade\TestBatch::class, $this->obj->getBatch());

		_elgg_services()->logger->enable();
	}
	
	public function testSetCompleted() {
		$upgrade = new ElggUpgrade();
		
		$upgrade->setCompleted();
		
		$this->assertTrue($upgrade->isCompleted());
		$this->assertNotEmpty($upgrade->is_completed);
		
		$this->assertNotEmpty($upgrade->getCompletedTime());
		$this->assertNotEmpty($upgrade->completed_time);
		
		$this->assertNotEmpty($upgrade->getStartTime());
		$this->assertNotEmpty($upgrade->start_time);
	}
	
	public function testSetStarttime() {
		$upgrade = new ElggUpgrade();
		
		$started = $upgrade->setStartTime();
		
		$this->assertNotEmpty($started);
		$this->assertEquals($started, $upgrade->getStartTime());
		$this->assertEquals($started, $upgrade->start_time);
		
		// try to override the start time, this is not allowed
		$override = $upgrade->setStartTime($started + 3600);
		$this->assertEquals($started, $override);
		$this->assertEquals($started, $upgrade->getStartTime());
		$this->assertEquals($started, $upgrade->start_time);
	}
	
	public function testReset() {
		$upgrade = new ElggUpgrade();
		
		$upgrade->is_completed = true;
		$upgrade->completed_time = time();
		$upgrade->processed = 100;
		$upgrade->offset = 20;
		$upgrade->start_time = time() - 100;
		
		$upgrade->reset();
		
		$this->assertEmpty($upgrade->is_completed);
		$this->assertEmpty($upgrade->completed_time);
		$this->assertEmpty($upgrade->processed);
		$this->assertEmpty($upgrade->offset);
		$this->assertEmpty($upgrade->start_time);
	}
}
