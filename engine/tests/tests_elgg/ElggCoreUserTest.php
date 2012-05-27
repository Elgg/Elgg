<?php
/**
 *  Copyright (C) 2011 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  IMPORTANT: The tests in this file were ported from the original
 *  elgg engine tests. Please see Elgg's README.txt, COPYRIGHT.txt 
 *  and CONTRIBUTORS.txt for copyright and contributor information.  
 */
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');

class ElggCoreUserTest extends ElggTestCase
{
	protected $user;
	
	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		$this->user = new ElggUserTest();
	}
	
	public function testElggUserConstructor() 
	{
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

		$this->assertEquals($entity_attributes, $attributes);
	}

	public function testElggUserLoad() 
	{
		// new object
		$object = new ElggObject();
		$this->assertNull($object->getGUID());
		$guid = $object->save();
		$this->assertNotEquals($guid, 0);

		// fail on wrong type
		try {
			$error = new ElggUserTest($guid);
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertInstanceOf('InvalidClassException', $e);
			$message = sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, 'ElggUser');
			$this->assertEquals($e->getMessage(), $message);
		}
	}

	public function testElggUserConstructorByGuid() 
	{
		//Used to be: $this->assertEquals($user, $_SESSION['user']);
		// $user = new ElggUser(elgg_get_logged_in_user_guid());
		// $this->assertEquals($user, $_SESSION['user']);
		//@link http://trac.elgg.org/ticket/4111
		$user = new ElggUser((int)elgg_get_logged_in_user_guid());
		$this->assertEquals($user->getGUID(), $_SESSION['user']->getGUID());
		
		// fail with garbage
		try {
			$error = new ElggUserTest(array('invalid'));
			$this->assertTrue(FALSE);
		} catch (Exception $e) {
			$this->assertInstanceOf('InvalidParameterException', $e);
			$message = sprintf(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			$this->assertEquals($e->getMessage(), $message);
		}
	}

	public function testElggUserConstructorByDbRow() 
	{
		$row = $this->fetchUser(elgg_get_logged_in_user_guid());
		$user = new ElggUser($row);
		//Same problem due to assertIdential() #4106
		$this->assertEquals($user->getGUID(), $_SESSION['user']->getGUID());
	}

	public function testElggUserConstructorByUsername() 
	{
		$row = $this->fetchUser(elgg_get_logged_in_user_guid());
		$user = new ElggUser($row->username);
		//Same problem due to assertIdential() #4106
		$this->assertEquals($user->getGUID(), $_SESSION['user']->getGUID());
	}

	public function testElggUserSave() 
	{
		// new object
		$this->assertNull($this->user->getGUID());
		$guid = $this->user->save();
		$this->assertNotEquals($guid, 0);
	}

	public function testElggUserDelete() 
	{
		$guid = $this->user->save();

		// delete object
		$this->assertEquals($this->user->delete(),1);

		// check GUID not in database
		$this->assertTrue($this->fetchUser($guid) == array());
	}

	public function testElggUserNameCache() 
	{
		// Trac #1305

		// very unlikely a user would have this username
		$name = (string)time();
		$this->user->username = $name;

		$guid = $this->user->save();

		$user = get_user_by_username($name);
		$user->delete();
		$user = get_user_by_username($name);
		//Used to be: $this->assertFalse($user);
		//@link http://trac.elgg.org/ticket/4112
		$this->assertEquals($user, array());
	}


	public function testElggUserMakeAdmin() 
	{
		global $CONFIG;

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		$q = "SELECT admin FROM {$CONFIG->dbprefix}users_entity WHERE guid = $guid";
		$r = mysql_query($q);

		$admin = mysql_fetch_assoc($r);
		$this->assertEquals($admin['admin'], 'yes');
	}

	public function testElggUserRemoveAdmin() 
	{
		global $CONFIG;

		// need to save user to have a guid
		$guid = $this->user->save();

		$this->assertTrue($this->user->removeAdmin());

		$q = "SELECT admin FROM {$CONFIG->dbprefix}users_entity WHERE guid = $guid";
		$r = mysql_query($q);

		$admin = mysql_fetch_assoc($r);
		$this->assertEquals($admin['admin'], 'no');
	}

	public function testElggUserIsAdmin() 
	{
		// need to grab a real user with a guid and everything.
		$guid = $this->user->save();

		$this->assertTrue($this->user->makeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertTrue($this->user->isAdmin());
	}

	public function testElggUserIsNotAdmin() 
	{
		// need to grab a real user with a guid and everything.
		$guid = $this->user->save();

		$this->assertTrue($this->user->removeAdmin());

		// this is testing the function, not the SQL.
		// that's been tested above.
		$this->assertFalse($this->user->isAdmin());
	}

	protected function fetchUser($guid)
	{
		global $CONFIG;

		return get_data_row("SELECT * FROM {$CONFIG->dbprefix}users_entity WHERE guid = '$guid'");
	}
}

class ElggUserTest extends ElggUser 
{
	public function expose_attributes() 
	{
		return $this->attributes;
	}
}