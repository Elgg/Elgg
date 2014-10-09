<?php

class ElggUpgradeTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var ElggUpgrade
	 */
	protected $obj;

	protected function setUp() {
		// required by ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));

		$this->obj = $this->getMockBuilder('ElggUpgrade')
				->setMethods(null)
				->getMock();

		$this->obj->_callable_egefps = array($this, 'mock_egefps');
	}

	public function mock_egefps($options) {
		return array();
	}

	public function mock_egefps_with_entities() {
		return array(new stdClass());
	}

	public function mock_egefps_for_full_url($options) {
		return array(new stdClass());
	}

	public function mock_egefps_for_path($options) {
		if ($options['private_setting_value'] === 'test') {
			return array(new stdClass());
		} else {
			return array();
		}
	}

	public function testDefaultAttrs() {
		$this->assertSame('elgg_upgrade', $this->obj->subtype);
		$this->assertSame(0, $this->obj->container_guid);
		$this->assertSame(0, $this->obj->owner_guid);
		$this->assertSame(0, $this->obj->is_completed);
	}

	public function testSetPath() {
		$path = 'admin/upgrades';
		$this->obj->setPath($path);
		$this->assertSame(elgg_normalize_url($path), $this->obj->getURL());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsOnBadPath() {
		$path = false;
		$this->obj->setPath($path);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsOnDuplicatePath() {
		$this->obj->_callable_egefps = array($this, 'mock_egefps_with_entities');
		$path = 'admin/upgrades';
		$this->obj->setPath($path);
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the upgrade_url property.
	 */
	public function testThrowsOnSaveWithoutPath() {
		$this->obj->description = 'Test';
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the title property.
	 */
	public function testThrowsOnSaveWithoutTitle() {
		$this->obj->setPath('test');
		$this->obj->description = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade objects must have a value for the description property.
	 */
	public function testThrowsOnSaveWithoutDesc() {
		$this->obj->setPath('test');
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	public function testCanFindUpgradesByPath() {
		$this->obj->_callable_egefps = array($this, 'mock_egefps_for_path');
		$upgrade = $this->obj->getUpgradeFromPath('test');
		$this->assertTrue((bool)$upgrade);
	}

	public function testCanFindUpgradesByFullUrl() {
		$this->obj->_callable_egefps = array($this, 'mock_egefps_for_full_url');
		$this->obj->upgrade_url = elgg_normalize_url('test');
		$upgrade = $this->obj->getUpgradeFromPath('test');
		$this->assertTrue((bool)$upgrade);
		$this->assertSame('test', $upgrade->upgrade_url);
	}

	// can't test save without db mocking

}