<?php
/**
 * Elgg Test ElggSite
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreSiteTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->site = new ElggSiteTest;
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->swallowErrors();
		unset($this->site);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		parent::__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggSiteConstructor() {
		$attributes = array();
		$attributes['guid'] = '';
		$attributes['type'] = 'site';
		$attributes['subtype'] = '';
		$attributes['owner_guid'] = get_loggedin_userid();
		$attributes['container_guid'] = get_loggedin_userid();
		$attributes['site_guid'] = 0;
		$attributes['access_id'] = ACCESS_PRIVATE;
		$attributes['time_created'] = '';
		$attributes['time_updated'] = '';
		$attributes['enabled'] = 'yes';
		$attributes['tables_split'] = 2;
		$attributes['tables_loaded'] = 0;
		$attributes['name'] = '';
		$attributes['description'] = '';
		$attributes['url'] = '';

		$this->assertIdentical($this->site->expose_attributes(), $attributes);
	}
	
	public function testElggSiteSaveAndDelete() {
		$this->assertTrue($this->site->save());
		$this->assertTrue($this->site->delete());
	}
}

class ElggSiteTest extends ElggSite {
	public function expose_attributes() {
		return $this->attributes;
	}
}
