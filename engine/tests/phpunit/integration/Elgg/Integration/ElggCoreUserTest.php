<?php

namespace Elgg\Integration;

use Elgg\Database\EntityTable;
use Elgg\Database\Select;

class ElggCoreUserTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());

		$this->user = new \ElggUser();
		$this->user->username = $this->getRandomUsername();
		$this->user->setSubtype($this->getRandomSubtype());
		$this->user->owner_guid = 0;
		$this->user->container_guid = 0;
	}

	public function down() {
		if (isset($this->user)) {
			$this->user->delete();
		}

		unset($this->user);
	}

	public function testElggUserLoad() {
		// new object
		$object = $this->createObject();
		
		// fail on wrong type
		$this->assertNull(get_user($object->guid));
	}

	public function testElggUserSave() {
		// new object
		$this->assertEquals(0, $this->user->getGUID());
		$this->assertTrue($this->user->save());
		$this->assertGreaterThan(0, $this->user->guid);
	}

	public function testElggUserDelete() {
		$this->assertTrue($this->user->save());
		$guid = $this->user->guid;
		$this->assertGreaterThan(0, $guid);

		// delete object
		$this->assertTrue($this->user->delete());
		unset($this->user);
		
		// check GUID not in database
		$this->assertEmpty($this->fetchUser($guid));
	}
	
	public function testElggUserNameCache() {
		// issue https://github.com/elgg/elgg/issues/1305

		// very unlikely a user would have this username
		$name = "user_" . time();
		$this->user->username = $name;

		$this->assertTrue($this->user->save());

		$db_user = $this->fetchUser($this->user->guid);
		$this->assertNotEmpty($db_user);

		$user = elgg_get_user_by_username($name);

		$this->assertTrue($user->delete());
		unset($this->user);

		$user = elgg_get_user_by_username($name);
		$this->assertNull($user);
	}

	public function testGetUserByUsernameAcceptsUrlEncoded() {
		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$this->assertTrue($this->user->save());

		// percent encode first letter
		$first_letter = $username[0];
		$first_letter = str_pad('%' . dechex(ord($first_letter)), 2, '0', STR_PAD_LEFT);
		$username =   $first_letter . substr($username, 1);

		$user = elgg_get_user_by_username($username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $this->user->guid);
	}
	
	public function testGetUserByUsernameCaseInsensitivity() {
		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$this->assertTrue($this->user->save());

		$uc_username = strtoupper($username);
		
		$user = elgg_get_user_by_username($uc_username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $this->user->guid);
	}
	
	public function testGetUserByEmailCaseInsensitivity() {
		$email = 'Example.User@elgg.org';
		$this->user->email = $email;
		$this->assertTrue($this->user->save());

		$user = elgg_get_user_by_email($email);
		
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertEquals($user->guid, $this->user->guid);
		
		// lower case
		$email = strtolower($email);
		$user = elgg_get_user_by_email($email);
		
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertEquals($user->guid, $this->user->guid);
		
		// get by email username
		$user = elgg_get_user_by_username($email, true);
		
		$this->assertInstanceOf(\ElggUser::class, $user);
		$this->assertEquals($user->guid, $this->user->guid);
	}

	public function testElggUserMakeAdmin() {
		// need to save user to have a guid
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		$this->assertTrue($this->user->isAdmin());
	}

	public function testElggUserRemoveAdmin() {
		// need to save user to have a guid
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());
		
		$this->assertTrue($this->user->removeAdmin());

		$this->assertFalse($this->user->isAdmin());
	}

	public function testElggUserIsAdmin() {
		// need to grab a real user with a guid and everything.
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertTrue($this->user->isAdmin());
	}

	public function testElggUserIsNotAdmin() {
		// need to grab a real user with a guid and everything.
		$this->user->save();

		$this->assertTrue($this->user->removeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertFalse($this->user->isAdmin());
	}

	public function testElggUserNotificationSettings() {
		elgg_register_notification_method('method1');
		elgg_register_notification_method('method2');

		$this->user->setNotificationSetting('method1', true);
		$this->user->setNotificationSetting('method2', false);
		$this->user->setNotificationSetting('method3', true);

		$settings = $this->user->getNotificationSettings();
		$this->assertTrue($settings['method1']);
		$this->assertFalse($settings['method2']);
		$this->assertArrayNotHasKey('method3', $settings);

		$enabled_methods = $this->user->getNotificationSettings('default', true);
		$this->assertContains('method1', $enabled_methods);
		$this->assertNotContains('methods2', $enabled_methods);
		$this->assertNotContains('methods3', $enabled_methods);
	}
	
	/**
	 * @dataProvider profileDataProvider
	 */
	public function testSavePrivateProfileData($name, $value) {
		$profile_user = $this->createUser();
		$reading_user = $this->createUser();
		
		$session = _elgg_services()->session_manager;
		
		// store profile data
		$session->setLoggedInUser($profile_user);
		
		$profile_user->setProfileData($name, $value, ACCESS_PRIVATE);
		
		// correctly stored
		$this->assertEquals($value, $profile_user->getProfileData($name));
		$this->assertEquals($value, $profile_user->$name); // metadata BC
		
		// try to read as different user
		$session->setLoggedInUser($reading_user);
		
		$this->assertEmpty($profile_user->getProfileData($name));
	}
	
	/**
	 * @dataProvider profileDataProvider
	 */
	public function testSavePublicProfileData($name, $value) {
		$profile_user = $this->createUser();
		$reading_user = $this->createUser();
		
		$session = _elgg_services()->session_manager;
		
		// store profile data
		$session->setLoggedInUser($profile_user);
		
		$profile_user->setProfileData($name, $value, ACCESS_PUBLIC);
		
		// correctly stored
		$this->assertEquals($value, $profile_user->getProfileData($name));
		$this->assertEquals($value, $profile_user->$name); // metadata BC
		
		// try to read as different user
		$session->setLoggedInUser($reading_user);
		
		$this->assertEquals($value, $profile_user->getProfileData($name));
	}
	
	public static function profileDataProvider() {
		return [
			['field_a', 'value'],
			['field_b', 123],
			['field_a', ['foo', 'bar']],
			['field_b', [123, 456]],
			['field_c', ['foo', 123]],
			['field_d', null],
		];
	}
	
	/**
	 * @dataProvider emptyProfileDataProvider
	 */
	public function testSaveEmptyProfileData($value) {
		$user = $this->createUser();
		
		$user->setProfileData('foo', 'bar');
		
		$this->assertEquals('bar', $user->getProfileData('foo'));
		
		$user->setProfileData('foo', $value);
		
		$this->assertEmpty($user->getProfileData('foo'));
	}
	
	public static function emptyProfileDataProvider() {
		return [
			[''],
			[null],
		];
	}

	protected function fetchUser($guid) {
		$qb = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$qb->select("{$qb->getTableAlias()}.*");
		$qb->where($qb->compare("{$qb->getTableAlias()}.guid", '=', $guid, ELGG_VALUE_INTEGER));

		return _elgg_services()->db->getDataRow($qb);
	}
}
