<?php
/**
 * Elgg Test ElggUser
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreUserTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();

		// all code should come after here
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->user = new ElggUserTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {

		unset($this->user);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all code should go above here
		parent::__destruct();
	}

	public function testElggUserConstructor() {
		$attributes = array();
		$attributes['guid'] = null;
		$attributes['type'] = 'user';
		$attributes['subtype'] = null;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$attributes['site_guid'] = null;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = null;
		$attributes['time_updated'] = null;
		$attributes['last_action'] = null;
		$attributes['enabled'] = 'yes';
 		$attributes['name'] = null;
		$attributes['username'] = null;
		$attributes['password'] = null;
		$attributes['salt'] = null;
		$attributes['email'] = null;
		$attributes['language'] = null;
		$attributes['banned'] = 'no';
		$attributes['admin'] = 'no';
		$attributes['prev_last_action'] = null;
		$attributes['last_login'] = null;
		$attributes['prev_last_login'] = null;
		ksort($attributes);

		$entity_attributes = $this->user->expose_attributes();
		ksort($entity_attributes);

		$this->assertIdentical($entity_attributes, $attributes);
	}

	public function testElggUserLoad() {
		// new object
		$object = new ElggObject();
		$this->AssertEqual($object->getGUID(), 0);
		$guid = $object->save();
		$this->AssertNotEqual($guid, 0);

		// fail on wrong type
		$this->assertFalse(get_user($guid));

		// clean up
		$object->delete();
	}

	public function testElggUserConstructorWithGarbage() {
		try {
			$error = new ElggUserTest(array('invalid'));
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
		}
	}

	public function testElggUserConstructorByDbRow() {
		$row = $this->fetchUser(elgg_get_logged_in_user_guid());
		$user = new ElggUser($row);
		$this->assertIdenticalEntities($user, $_SESSION['user']);
	}

	public function testElggUserSave() {
		// new object
		$this->AssertEqual($this->user->getGUID(), 0);
		$guid = $this->user->save();
		$this->AssertNotEqual($guid, 0);

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
		$user->delete();
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
		global $CONFIG;

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		$q = "SELECT admin FROM {$CONFIG->dbprefix}users_entity WHERE guid = $guid";
		$r = mysql_query($q);

		$admin = mysql_fetch_assoc($r);
		$this->assertEqual($admin['admin'], 'yes');

		$this->user->delete();
	}

	public function testElggUserRemoveAdmin() {
		global $CONFIG;

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->removeAdmin());

		$q = "SELECT admin FROM {$CONFIG->dbprefix}users_entity WHERE guid = $guid";
		$r = mysql_query($q);

		$admin = mysql_fetch_assoc($r);
		$this->assertEqual($admin['admin'], 'no');

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

	protected function fetchUser($guid) {
		global $CONFIG;

		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}users_entity WHERE guid = '$guid'");
	}
}

class ElggUserTest extends ElggUser {
	public function expose_attributes() {
		return $this->attributes;
	}
}
