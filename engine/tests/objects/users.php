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
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();

		unset($this->user);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all code should go above here
		parent::__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggUserConstructor() {
		$attributes = array();
		$attributes['guid'] = NULL;
		$attributes['type'] = 'user';
		$attributes['subtype'] = NULL;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$attributes['site_guid'] = NULL;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = NULL;
		$attributes['time_updated'] = NULL;
		$attributes['last_action'] = NULL;
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['name'] = NULL;
		$attributes['username'] = NULL;
		$attributes['password'] = NULL;
		$attributes['salt'] = NULL;
		$attributes['email'] = NULL;
		$attributes['language'] = NULL;
		$attributes['code'] = NULL;
		$attributes['banned'] = 'no';
		$attributes['admin'] = 'no';
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
		try {
			$error = new ElggUserTest($guid);
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidClassException');
			$message = sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, 'ElggUser');
			$this->assertIdentical($e->getMessage(), $message);
		}

		// clean up
		$object->delete();
	}

	public function testElggUserConstructorByGuid() {
		$user = new ElggUser(elgg_get_logged_in_user_guid());
		$this->assertIdentical($user, $_SESSION['user']);

		// fail with garbage
		try {
			$error = new ElggUserTest(array('invalid'));
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertIsA($e, 'InvalidParameterException');
			$message = sprintf(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			$this->assertIdentical($e->getMessage(), $message);
		}
	}

	public function testElggUserConstructorByDbRow() {
		$row = $this->fetchUser(elgg_get_logged_in_user_guid());
		$user = new ElggUser($row);
		$this->assertIdentical($user, $_SESSION['user']);
	}

	public function testElggUserConstructorByUsername() {
		$row = $this->fetchUser(elgg_get_logged_in_user_guid());
		$user = new ElggUser($row->username);
		$this->assertIdentical($user, $_SESSION['user']);
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
		$this->assertTrue($this->user->delete());

		// check GUID not in database
		$this->assertFalse($this->fetchUser($guid));
	}

	public function testElggUserNameCache() {
		// Trac #1305

		// very unlikely a user would have this username
		$name = (string)time();
		$this->user->username = $name;

		$guid = $this->user->save();

		$user = get_user_by_username($name);
		$user->delete();
		$user = get_user_by_username($name);
		$this->assertFalse($user);
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

	// remove in 1.9
	public function testElggUserIsAdminLegacy() {
		$this->user->save();
		$this->user->makeAdmin();

		$this->assertTrue($this->user->admin);
		$this->assertTrue($this->user->siteadmin);

		$this->user->removeAdmin();
		$this->user->delete();
	}

	public function testElggUserIsNotAdminLegacy() {
		$this->user->save();
		$this->user->removeAdmin();

		$this->assertFalse($this->user->admin);
		$this->assertFalse($this->user->siteadmin);

		$this->user->removeAdmin();
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
