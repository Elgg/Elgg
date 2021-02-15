<?php

namespace Elgg\Integration;

use Elgg\Database\Select;
use Elgg\Helpers\ElggUserWithExposableAttributes;

/**
 * Elgg Test \ElggUser
 *
 * @group IntegrationTests
 * @group ElggUser
 */
class ElggCoreUserTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggUserWithExposableAttributes
	 */
	private $user;

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());

		$this->user = new ElggUserWithExposableAttributes();
		$this->user->username = $this->getRandomUsername();
		$this->user->setSubtype($this->getRandomSubtype());
		$this->user->owner_guid = 0;
		$this->user->container_guid = 0;
	}

	public function down() {
		if ($this->user) {
			$this->user->delete();
		}
		unset($this->user);

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testElggUserLoad() {
		// new object
		$object = new \ElggObject();
		$object->setSubtype($this->getRandomSubtype());
		$this->assertEquals(0, $object->getGUID());
		$this->assertTrue($object->save());
		$this->assertGreaterThan(0, $object->guid);

		// fail on wrong type
		$this->assertFalse(get_user($object->guid));

		// clean up
		$object->delete();
	}

	public function testElggUserSave() {
		// new object
		$this->assertEquals(0, $this->user->getGUID());
		$this->assertTrue($this->user->save());
		$this->assertGreaterThan(0, $this->user->guid);

		// clean up
		$this->user->delete();
	}

	public function testElggUserDelete() {
		$this->assertTrue($this->user->save());
		$guid = $this->user->guid;
		$this->assertGreaterThan(0, $guid);

		// delete object
		$this->assertTrue($this->user->delete());

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

		$user = get_user_by_username($name);

		$this->assertTrue($user->delete());

		$user = get_user_by_username($name);
		$this->assertFalse($user);
	}

	public function testGetUserByUsernameAcceptsUrlEncoded() {

		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$this->assertTrue($this->user->save());

		// percent encode first letter
		$first_letter = $username[0];
		$first_letter = str_pad('%' . dechex(ord($first_letter)), 2, '0', STR_PAD_LEFT);
		$username =   $first_letter . substr($username, 1);

		$user = get_user_by_username($username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $this->user->guid);

		$this->user->delete();
	}
	
	public function testGetUserByUsernameCaseInsensitivity() {

		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$this->assertTrue($this->user->save());

		$uc_username = strtoupper($username);
		
		$user = get_user_by_username($uc_username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $this->user->guid);

		$this->user->delete();
	}
	
	public function testGetUserByEmailCaseInsensitivity() {

		$email = 'Example.User@elgg.org';
		$this->user->email = $email;
		$this->assertTrue($this->user->save());

		$users = get_user_by_email($email);
		
		$this->assertCount(1, $users);
		$this->assertEquals($users[0]->guid, $this->user->guid);
		
		// lower case
		$email = strtolower($email);
		$users = get_user_by_email($email);
		
		$this->assertCount(1, $users);
		$this->assertEquals($users[0]->guid, $this->user->guid);

		$this->user->delete();
	}

	public function testElggUserMakeAdmin() {
		// need to save user to have a guid
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		$this->assertTrue($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserRemoveAdmin() {
		// need to save user to have a guid
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());
		
		$this->assertTrue($this->user->removeAdmin());

		$this->assertFalse($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserIsAdmin() {
		// need to grab a real user with a guid and everything.
		$this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertTrue($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserIsNotAdmin() {
		// need to grab a real user with a guid and everything.
		$this->user->save();

		$this->assertTrue($this->user->removeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertFalse($this->user->isAdmin());

		$this->user->delete();
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
		$this->assertTrue(!isset($settings['method3']));

		$this->user->delete();
	}

	public function testCanDisableUserEntities() {

		$user = $this->createUser();
		$this->createObject([
			'owner_guid' => $user->guid,
		]);
		$this->createObject([
			'container_guid' => $user->guid,
		]);

		_elgg_services()->entityTable->disableEntities($user);

		$objects = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
			return elgg_get_entities([
				'owner_guid' => $user->guid,
			]);
		});
		
		foreach ($objects as $object) {
			$this->assertFalse($object->isEnabled());
		}
	}
	
	/**
	 * @dataProvider profileDataProvider
	 */
	public function testSavePrivateProfileData($name, $value) {
		$profile_user = $this->createUser();
		$reading_user = $this->createUser();
		
		$session = elgg_get_session();
		
		// store profile data
		$session->setLoggedInUser($profile_user);
		
		$profile_user->setProfileData($name, $value, ACCESS_PRIVATE);
		
		// correctly stored
		$this->assertEquals($value, $profile_user->getProfileData($name));
		$this->assertEquals($value, $profile_user->$name); // metadata BC
		
		// try to read as different user
		$session->setLoggedInUser($reading_user);
		
		$this->assertEmpty($profile_user->getProfileData($name));
		
		// cleanup
		$session->removeLoggedInUser();
		$profile_user->delete();
		$reading_user->delete();
	}
	
	/**
	 * @dataProvider profileDataProvider
	 */
	public function testSavePublicProfileData($name, $value) {
		$profile_user = $this->createUser();
		$reading_user = $this->createUser();
		
		$session = elgg_get_session();
		
		// store profile data
		$session->setLoggedInUser($profile_user);
		
		$profile_user->setProfileData($name, $value, ACCESS_PUBLIC);
		
		// correctly stored
		$this->assertEquals($value, $profile_user->getProfileData($name));
		$this->assertEquals($value, $profile_user->$name); // metadata BC
		
		// try to read as different user
		$session->setLoggedInUser($reading_user);
		
		$this->assertEquals($value, $profile_user->getProfileData($name));
		
		// cleanup
		$session->removeLoggedInUser();
		$profile_user->delete();
		$reading_user->delete();
	}
	
	public function profileDataProvider() {
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
		
		$user->delete();
	}
	
	public function emptyProfileDataProvider() {
		return [
			[''],
			[null],
		];
	}

	protected function fetchUser($guid) {
		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.*');
		$qb->where($qb->compare('e.guid', '=', $guid, ELGG_VALUE_INTEGER));

		return _elgg_services()->db->getDataRow($qb);
	}
}
