<?php

/**
 * @group UpgradeService
 */
class ElggUpgradeUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var ElggUpgrade
	 */
	protected $obj;

	public function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());

		$this->obj = $this->getMockBuilder('\ElggUpgrade')
				->setMethods(null)
				->getMock();

		$this->obj->_callable_egefps = [$this, 'mock_egefps'];

		_elgg_services()->logger->disable();
	}

	public function tearDown() {
		_elgg_services()->logger->enable();
	}
	
	public function mock_egefps($options) {
		return [];
	}

	public function mock_egefps_with_entities() {
		return [new \stdClass()];
	}

	public function mock_egefps_for_path($options) {
		if ($options['private_setting_value'] === 'test') {
			return [new \stdClass()];
		} else {
			return [];
		}
	}

	public function testDefaultAttrs() {
		$this->assertSame('elgg_upgrade', $this->obj->subtype);
		$this->assertSame(0, $this->obj->container_guid);
		$this->assertSame(0, $this->obj->owner_guid);
		$this->assertSame(0, $this->obj->is_completed);
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the class property.
	 */
	public function testThrowsOnSaveWithoutClass() {
		$this->obj->description = 'Test';
		$this->obj->id = 'test';
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the title property.
	 */
	public function testThrowsOnSaveWithoutTitle() {
		$this->obj->setClass('test');
		$this->obj->description = 'Test';
		$this->obj->id = 'test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the description property.
	 */
	public function testThrowsOnSaveWithoutDesc() {
		$this->obj->setClass('test');
		$this->obj->id = 'test';
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the id property.
	 */
	public function testThrowsOnSaveWithoutId() {
		$this->obj->setClass('test');
		$this->obj->description = 'Test';
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	public function testCanInstantiateBatchRunner() {
		$this->obj->setClass('\InvalidClass');
		$this->assertFalse($this->obj->getBatch());

		$this->obj->setClass(\Elgg\Upgrade\InvalidBatch::class);
		$this->assertFalse($this->obj->getBatch());

		$this->obj->setClass(\Elgg\Upgrade\TestBatch::class);
		$this->assertInstanceOf(\Elgg\Upgrade\TestBatch::class, $this->obj->getBatch());
	}
}
