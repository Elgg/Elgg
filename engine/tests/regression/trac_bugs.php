<?php
/**
 * Elgg Regression Tests -- Trac Bugfixes
 * Any bugfixes from Trac that require testing belong here.
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreRegressionBugsTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		$this->ia = elgg_set_ignore_access(TRUE);
		parent::__construct();
		
		// all __construct() code should come after here
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		elgg_set_ignore_access($this->ia);
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * #1558
	 */
	public function testElggObjectClearAnnotations() {
		$this->entity = new ElggObject();
		$guid = $this->entity->save();
		
		$this->entity->annotate('test', 'hello', ACCESS_PUBLIC);
		
		$this->entity->clearAnnotations('does not exist');
		
		$num = $this->entity->countAnnotations('test');
		
		//$this->assertIdentical($num, 1);
		$this->assertEqual($num, 1);
		
		// clean up
		$this->entity->delete();
	}
	
	/**
	 * #2063 - get_resized_image_from_existing_file() fails asked for image larger than selection and not scaling an image up
	 * Test get_image_resize_parameters().
	 */
	public function testElggResizeImage() {
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
		
		$this->assertEqual($params['newwidth'], $options['maxwidth']);
		$this->assertEqual($params['newheight'], $options['maxheight']);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);
		
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
		
		$this->assertEqual($params['newwidth'], 25);
		$this->assertEqual($params['newheight'], 25);
		$this->assertEqual($params['xoffset'], $options['x1']);
		$this->assertEqual($params['yoffset'], $options['y1']);
	}

	// #3722 Check canEdit() works for contains regardless of groups
	function test_can_write_to_container() {
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
		
		register_plugin_hook('container_permissions_check', 'all', 'can_write_to_container_test_hook');
		$this->assertTrue(can_write_to_container($user->guid, $object->guid));
		unregister_plugin_hook('container_permissions_check', 'all', 'can_write_to_container_test_hook');

		$this->assertFalse(can_write_to_container($user->guid, $group->guid));
		$group->join($user);
		$this->assertTrue(can_write_to_container($user->guid, $group->guid));

		elgg_set_ignore_access($ia);

		$user->delete();
		$object->delete();
		$group->delete();
	}
}
