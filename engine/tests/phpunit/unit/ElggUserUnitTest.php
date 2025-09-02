<?php

use Elgg\Exceptions\InvalidArgumentException;

class ElggUserUnitTest extends \Elgg\UnitTestCase {

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggUser());
	}

	public function testCanSetNotificationSettings() {

		$obj = $this->getMockBuilder(ElggUser::class)
				->onlyMethods(['save'])
				->getMock();
		$obj->expects($this->any())
				->method('save')
				->willReturn(true);

		_elgg_services()->notifications->registerMethod('registered1');
		_elgg_services()->notifications->registerMethod('registered2');

		$obj->setNotificationSetting('registered1', true);
		$obj->setNotificationSetting('registered2', false);
		$obj->setNotificationSetting('unregistered', true);
		
		$user_settings = $obj->getNotificationSettings();
		$this->assertTrue($user_settings['registered1']);
		$this->assertFalse($user_settings['registered2']);
		$this->assertArrayNotHasKey('unregistered', $user_settings);
		
		$enabled_methods = $obj->getNotificationSettings('default', true);
		$this->assertEquals(['registered1'], $enabled_methods);
	}
	
	public function testCanSetNotificationSettingsWithPurpose() {

		$obj = $this->getMockBuilder(ElggUser::class)
				->onlyMethods(['save'])
				->getMock();
		$obj->expects($this->any())
				->method('save')
				->willReturn(true);

		_elgg_services()->notifications->registerMethod('registered1');
		_elgg_services()->notifications->registerMethod('registered2');

		$obj->setNotificationSetting('registered1', false);
		$obj->setNotificationSetting('registered1', true, 'my_purpose');
		$obj->setNotificationSetting('registered2', true); // test fallback
		$obj->setNotificationSetting('unregistered', true);
		
		$user_settings = $obj->getNotificationSettings('my_purpose');
		$this->assertTrue($user_settings['registered1']);
		$this->assertTrue($user_settings['registered2']);
		$this->assertArrayNotHasKey('unregistered', $user_settings);
		
		$enabled_methods = $obj->getNotificationSettings('my_purpose', true);
		$this->assertEquals(['registered1', 'registered2'], $enabled_methods);
	}

	public function testCanExport() {
		$user = $this->createUser();

		$export = $user->toObject();

		$this->assertEquals($user->guid, $export->guid);
		$this->assertEquals($user->type, $export->type);
		$this->assertEquals($user->subtype, $export->subtype);
		$this->assertEquals($user->guid, $export->owner_guid);
		$this->assertEquals($user->time_created, $export->getTimeCreated()->getTimestamp());
		$this->assertEquals($user->time_updated, $export->getTimeUpdated()->getTimestamp());
		$this->assertEquals($user->getURL(), $export->url);
		$this->assertEquals($user->language, $export->language);
	}

	public function testCanSerialize() {
		$user = $this->createUser();

		$data = serialize($user);

		$unserialized = unserialize($data);

		$this->assertElggDataEquals($user, $unserialized);
	}

	public function testCanArrayAccessAttributes() {
		$user = $this->createUser();

		$this->assertEquals($user->guid, $user['guid']);

		foreach ($user as $attr => $value) {
			$this->assertEquals($user->$attr, $user[$attr]);
		}

		unset($user['access_id']);
	}

	public function testIsLoggable() {
		$unsaved = new \ElggUser();
		$this->assertEmpty($unsaved->getSystemLogID());
		
		$user = $this->createUser();

		$this->assertEquals($user->guid, $user->getSystemLogID());
		$this->assertElggDataEquals($user, $user->getObjectFromID($user->guid));
	}
	
	public function testDefaultUserMetadata() {
		$user = new \ElggUser();
		
		$this->assertEquals(0, $user->prev_last_action);
		$this->assertEquals(0, $user->last_login);
		$this->assertEquals(0, $user->prev_last_login);
		$this->assertEquals('no', $user->banned);
		$this->assertEquals('no', $user->admin);
		$this->assertNotEmpty($user->language);
		$this->assertIsString($user->language);
	}
	
	/**
	 * @dataProvider protectedValues
	 */
	public function testSetProtectedValuesThrowsException($name) {
		$user = $this->createUser();
		
		$this->expectException(InvalidArgumentException::class);
		$user->$name = 'foo';
	}
	
	public static function protectedValues() {
		return [
			['admin'],
			['banned'],
		];
	}
	
	public function testCantComment() {
		
		$user = $this->createUser();
		
		$this->assertFalse($user->canComment());
		
		$user2 = $this->createUser();
		
		_elgg_services()->session_manager->setLoggedInUser($user2);
		
		$this->assertFalse($user->canComment());
	}
	
	public function testGetDisplaynameReturnsString() {
		$user = new ElggUser();
		$this->assertEquals('', $user->getDisplayName());
		
		$user->name = 'foo';
		$this->assertEquals('foo', $user->getDisplayName());
	}
}
