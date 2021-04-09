<?php

use Elgg\Exceptions\InvalidArgumentException;

/**
 * @group User
 * @group UnitTests
 * @group ElggData
 */
class ElggUserUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggUser());
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
	
	public function testCanSetNotificationSettingsWithPurpose() {

		$obj = $this->getMockBuilder(ElggUser::class)
				->setMethods(['save'])
				->getMock();
		$obj->expects($this->any())
				->method('save')
				->will($this->returnValue(true));

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

		$this->assertEquals($user, $unserialized);
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
		$user = $this->createUser();

		$this->assertEquals($user->guid, $user->getSystemLogID());
		$this->assertEquals($user, $user->getObjectFromID($user->guid));
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
	
	public function protectedValues() {
		return [
			['admin'],
			['banned'],
		];
	}
	
	public function testCantComment() {
		
		$user = $this->createUser();
		
		$this->assertFalse($user->canComment());
		
		$user2 = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user2);
		
		$this->assertFalse($user->canComment());
		
		$session->removeLoggedInUser();
	}
}
