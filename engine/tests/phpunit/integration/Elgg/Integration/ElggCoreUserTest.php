<?php

namespace Elgg\Integration;
use Elgg\Database\Select;

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
		$this->user->subtype = $this->getRandomSubtype();
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
		$object->subtype = $this->getRandomSubtype();
		$this->assertEquals(0, $object->getGUID());
		$guid = $object->save();
		$this->assertNotEquals(0, $guid);

		// fail on wrong type
		$this->assertFalse(get_user($guid));

		// clean up
		$object->delete();
	}

	public function testElggUserSave() {
		// new object
		$this->assertEquals(0, $this->user->getGUID());
		$guid = $this->user->save();
		$this->assertNotEquals(0, $guid);

		// clean up
		$this->user->delete();
	}

	public function testElggUserDelete() {
		$guid = $this->user->save();

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

		$guid = $this->user->save();

		$db_user = $this->fetchUser($guid);
		$this->assertNotEmpty($db_user);

		$user = get_user_by_username($name);

		$this->assertTrue($user->delete());

		$user = get_user_by_username($name);
		$this->assertFalse($user);
	}

	public function testGetUserByUsernameAcceptsUrlEncoded() {

		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$guid = $this->user->save();

		// percent encode first letter
		$first_letter = $username[0];
		$first_letter = str_pad('%' . dechex(ord($first_letter)), 2, '0', STR_PAD_LEFT);
		$username =   $first_letter . substr($username, 1);

		$user = get_user_by_username($username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $guid);

		$this->user->delete();
	}
	
	public function testGetUserByUsernameCaseInsensitivity() {

		$username = $this->getRandomUsername();
		$this->user->username = $username;
		$guid = $this->user->save();

		$uc_username = strtoupper($username);
		
		$user = get_user_by_username($uc_username);
		$this->assertTrue((bool) $user);
		$this->assertEquals($user->guid, $guid);

		$this->user->delete();
	}
	
	public function testGetUserByEmailCaseInsensitivity() {

		$email = 'Example.User@elgg.org';
		$this->user->email = $email;
		$guid = $this->user->save();

		$users = get_user_by_email($email);
		
		$this->assertCount(1, $users);
		$this->assertEquals($users[0]->guid, $guid);
		
		// lower case
		$email = strtolower($email);
		$users = get_user_by_email($email);
		
		$this->assertCount(1, $users);
		$this->assertEquals($users[0]->guid, $guid);

		$this->user->delete();
	}

	public function testElggUserMakeAdmin() {
		$CONFIG = _elgg_config();

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		$this->assertTrue($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserRemoveAdmin() {
		$CONFIG = _elgg_config();

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());
		
		$this->assertTrue($this->user->removeAdmin());

		$this->assertFalse($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserIsAdmin() {
		// need to grab a real user with a guid and everything.
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertTrue($this->user->isAdmin());

		$this->user->delete();
	}

	public function testElggUserIsNotAdmin() {
		// need to grab a real user with a guid and everything.
		$guid = $this->user->save();

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

		disable_user_entities($user->guid);

		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$objects = elgg_get_entities([
			'owner_guid' => $user->guid,
		]);

		foreach ($objects as $object) {
			$this->assertFalse($object->isEnabled());
		}

		access_show_hidden_entities($ha);
	}

	protected function fetchUser($guid) {
		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.*');
		$qb->where($qb->compare('e.guid', '=', $guid, ELGG_VALUE_INTEGER));

		return _elgg_services()->db->getDataRow($qb);
	}
}

class ElggUserWithExposableAttributes extends \ElggUser {
	public function expose_attributes() {
		return $this->attributes;
	}
}