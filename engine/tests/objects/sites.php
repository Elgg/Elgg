<?php
/**
 * Elgg Test ElggSite
 *
 * @package Elgg
 * @subpackage Test
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
		$attributes['guid'] = NULL;
		$attributes['type'] = 'site';
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
		$attributes['description'] = NULL;
		$attributes['url'] = NULL;
		ksort($attributes);

		$entity_attributes = $this->site->expose_attributes();
		ksort($entity_attributes);

		$this->assertIdentical($entity_attributes, $attributes);
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
