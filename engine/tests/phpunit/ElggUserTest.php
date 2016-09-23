<?php

/**
 * @group User
 */
class ElggUserTest extends \Elgg\TestCase {

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggUser());
	}

	public function testSettingUnsettableAttributes() {
		$obj = new \ElggUser();
		foreach (array('prev_last_action', 'last_login', 'prev_last_login') as $name) {
			$obj->$name = 'foo';
			$this->assertNotEquals('foo', $obj->$name);
		}
	}

	public function testCanSetNotificationSettings() {

		$obj = $this->getMockBuilder(ElggUser::class)
				->setMethods(['save'])
				->getMock();
		$obj->expects($this->any())
				->method('save')
				->will($this->returnValue(true));

		_elgg_services()->notifications->registerMethod('registered1');
		_elgg_services()->notifications->registerMethod('registered2');

		$obj->setNotificationSetting('registered1', true);
		$obj->setNotificationSetting('registered2', false);
		$obj->setNotificationSetting('unregistered', true);
		
		$user_settings = $obj->getNotificationSettings();
		$this->assertTrue($user_settings['registered1']);
		$this->assertFalse($user_settings['registered2']);
		$this->assertArrayNotHasKey('unregistered', $user_settings);
	}

}
