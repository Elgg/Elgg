<?php

// Exceptions are translated
$engine = dirname(dirname(dirname(__FILE__)));
require_once "$engine/lib/languages.php";

class ElggUpgradeTest extends \PHPUnit_Framework_TestCase {
	protected $obj;

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', new \ElggSession(new \Elgg\Http\MockSessionStorage()));

		$this->obj = $this->getMockBuilder('\ElggUpgrade')
				->setMethods(null)
				->getMock();

		$this->obj->_callable_egefps = array($this, 'mock_egefps');
	}

	public function mock_egefps($options) {
		return array();
	}

	public function mock_egefps_with_entities() {
		return array(
			new \stdClass()
		);
	}

	public function testDefaultAttrs() {
		$this->assertSame('elgg_upgrade', $this->obj->subtype);
		$this->assertSame(0, $this->obj->container_guid);
		$this->assertSame(0, $this->obj->owner_guid);
		$this->assertSame(0, $this->obj->is_completed);
	}

	public function testSetURL() {
		$url = 'admin/upgrades';
		$this->obj->setURL($url);
		$this->assertSame(elgg_normalize_url($url), $this->obj->getURL());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsOnBadURL() {
		$url = false;
		$this->obj->setURL($url);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsOnDuplicateURL() {
		$this->obj->_callable_egefps = array($this, 'mock_egefps_with_entities');
		$url = 'admin/upgrades';
		$this->obj->setURL($url);
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade:error:upgrade_url_required
	 */
	public function testThrowsOnSaveWithoutURL() {
		$this->obj->description = 'Test';
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade:error:title_required
	 */
	public function testThrowsOnSaveWithoutTitle() {
		$this->obj->setURL('test');
		$this->obj->description = 'Test';
		$this->obj->save();
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage ElggUpgrade:error:description_required
	 */
	public function testThrowsOnSaveWithoutDesc() {
		$this->obj->setURL('test');
		$this->obj->title = 'Test';
		$this->obj->save();
	}

	// can't test save without db mocking

}