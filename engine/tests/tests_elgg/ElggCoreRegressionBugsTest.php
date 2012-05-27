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

class ElggCoreRegressionBugsTest extends ElggTestCase
{
	protected $ia; 
	protected $entity;
	
	/**
	 * Called before each test object.
	 */
	public function setUp() 
	{
		parent::setUp();
		
		$this->ia = elgg_set_ignore_access(TRUE);
	}
	
	/**
	 * This test case nneds to be run with an admin
	 * logged in.
	 * 
	 * @see ElggTestCase::doSetupLogin()
	 */
	protected function doSetupLogin()
	{
		$this->loginAsAdmin();
	}
	/**
	 * #1558
	 */
	public function testElggObjectClearAnnotations() 
	{
		$this->entity = new ElggObject();
		$guid = $this->entity->save();

		$this->entity->annotate('test', 'hello', ACCESS_PUBLIC);

		$this->entity->deleteAnnotations('does not exist');

		$num = $this->entity->countAnnotations('test');

		//$this->assertIdentical($num, 1);
		$this->assertEquals($num, 1);
	}

	/**
	 * #2063 - get_resized_image_from_existing_file() fails asked for image larger than selection and not scaling an image up
	 * Test get_image_resize_parameters().
	 */
	public function testElggResizeImage() 
	{
		$orig_width = 100;
		$orig_height = 150;

		// test against selection > max
		$options = array(
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => TRUE,
			'upscale' => FALSE,

			'x1' => 25,
			'y1' => 75,
			'x2' => 100,
			'y2' => 150
		);

		// should get back the same x/y offset == x1, y1 and an image of 50x50
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);

		$this->assertEquals($params['newwidth'], $options['maxwidth']);
		$this->assertEquals($params['newheight'], $options['maxheight']);
		$this->assertEquals($params['xoffset'], $options['x1']);
		$this->assertEquals($params['yoffset'], $options['y1']);

		// test against selection < max
		$options = array(
			'maxwidth' => 50,
			'maxheight' => 50,
			'square' => TRUE,
			'upscale' => FALSE,

			'x1' => 75,
			'y1' => 125,
			'x2' => 100,
			'y2' => 150
		);
		
		// should get back the same x/y offset == x1, y1 and an image of 25x25 because no upscale
		$params = get_image_resize_parameters($orig_width, $orig_height, $options);

		$this->assertEquals($params['newwidth'], 25);
		$this->assertEquals($params['newheight'], 25);
		$this->assertEquals($params['xoffset'], $options['x1']);
		$this->assertEquals($params['yoffset'], $options['y1']);
	}

	// #3722 Check canEdit() works for contains regardless of groups
	function test_can_write_to_container() 
	{
		$user = new ElggUser();
		$user->username = 'test_user_' . rand();
		$user->name = 'test_user_name_' . rand();
		$user->email = 'test@user.net';
		$user->container_guid = 0;
		$user->owner_guid = 0;
		$user->save();

		$object = new ElggObject();
		$object->save();

		$group = new ElggGroup();
		$group->save();
		
		// disable access overrides because we're admin.
		$ia = elgg_set_ignore_access(false);

		$this->assertFalse(can_write_to_container($user->guid, $object->guid));
		
		global $elgg_test_user;
		$elgg_test_user = $user;

		// register hook to allow access
		function can_write_to_container_test_hook($hook, $type, $value, $params) {
			global $elgg_test_user;

			if ($params['user']->getGUID() == $elgg_test_user->getGUID()) {
				return true;
			}
		}
		
		elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'can_write_to_container_test_hook');
		$this->assertTrue(can_write_to_container($user->guid, $object->guid));
		elgg_unregister_plugin_hook_handler('container_permissions_check', 'all', 'can_write_to_container_test_hook');

		$this->assertFalse(can_write_to_container($user->guid, $group->guid));
		$group->join($user);
		$this->assertTrue(can_write_to_container($user->guid, $group->guid));
	}

	function test_db_shutdown_links() 
	{
		global $DB_DELAYED_QUERIES, $test_results;
		$DB_DELAYED_QUERIES = array();

		function test_delayed_results($results) {
			global $test_results;
			$test_results = $results;
		}

		$q = 'SELECT 1 as test';

		$links = array('read', 'write', get_db_link('read'), get_db_link('write'));

		foreach ($links as $link) 
		{
			$DB_DELAYED_QUERIES = array();

			$result = execute_delayed_query($q, $link, 'test_delayed_results');

			$this->assertTrue($result, "Failed with link = $link");
			$this->assertEquals(count($DB_DELAYED_QUERIES), 1);
			$this->assertEquals($DB_DELAYED_QUERIES[0]['q'], $q);
			$this->assertEquals($DB_DELAYED_QUERIES[0]['l'], $link);
			$this->assertEquals($DB_DELAYED_QUERIES[0]['h'], 'test_delayed_results');

			db_delayedexecution_shutdown_hook();

			$num_rows = mysql_num_rows($test_results);
			$this->assertEquals($num_rows, 1);
			$row = mysql_fetch_assoc($test_results);
			$this->assertEquals($row['test'], 1);
		}

		// test bad case
		$DB_DELAYED_QUERIES = array();
		$result = execute_delayed_query($q, 'not_a_link', 'test_delayed_results');
		$this->assertFalse($result);
		$this->assertEquals(array(), $DB_DELAYED_QUERIES);
	}
}