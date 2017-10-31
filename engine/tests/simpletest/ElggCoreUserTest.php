<?php

/**
 * Elgg Test \ElggUser
 *
 * @package    Elgg
 * @subpackage Test
 */
class ElggCoreUserTest extends \ElggCoreUnitTest {

	public function up() {
		$this->user = new \ElggUserWithExposableAttributes();
		$this->user->username = $this->getRandomUsername();
		$this->user->subtype = $this->getRandomSubtype();
	}

	public function down() {
		unset($this->user);
	}

	public function testElggUserLoad() {
		// new object
		$object = new \ElggObject();
		$object->subtype = $this->getRandomSubtype();
		$this->assertEqual($object->getGUID(), 0);
		$guid = $object->save();
		$this->assertNotEqual($guid, 0);

		// fail on wrong type
		$this->assertFalse(get_user($guid));

		// clean up
		$object->delete();
	}

	public function testElggUserSave() {
		// new object
		$this->assertEqual($this->user->getGUID(), 0);
		$guid = $this->user->save();
		$this->assertNotEqual($guid, 0);

		// clean up
		$this->user->delete();
	}

	public function testElggUserDelete() {
		$guid = $this->user->save();

		// delete object
		$this->assertIdentical(true, $this->user->delete());

		// check GUID not in database
		$this->assertFalse($this->fetchUser($guid));
	}

	public function testElggUserNameCache() {
		// issue https://github.com/elgg/elgg/issues/1305

		// very unlikely a user would have this username
		$name = (string)time();
		$this->user->username = $name;

		$guid = $this->user->save();

		$user = get_user_by_username($name);

		$this->assertTrue($user->delete());

		$user = get_user_by_username($name);
		$this->assertFalse($user);
	}

	public function testGetUserByUsernameAcceptsUrlEncoded() {
		$username = (string)time();
		$this->user->username = $username;
		$guid = $this->user->save();

		// percent encode first letter
		$first_letter = $username[0];
		$first_letter = str_pad('%' . dechex(ord($first_letter)), 2, '0', STR_PAD_LEFT);
		$username =   $first_letter . substr($username, 1);

		$user = get_user_by_username($username);
		$this->assertTrue((bool) $user);
		$this->assertEqual($guid, $user->guid);

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

	protected function fetchUser($guid) {
		$CONFIG = _elgg_config();

		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '$guid'");
	}
}

class ElggUserWithExposableAttributes extends \ElggUser {
	public function expose_attributes() {
		return $this->attributes;
	}
}