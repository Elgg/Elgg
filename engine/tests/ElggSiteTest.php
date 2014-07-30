<?php
/**
 * Elgg Test \ElggSite
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreSiteTest extends \ElggCoreUnitTest {

	/**
	 * @var \ElggSite
	 */
	public $site;

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
		$this->site = new \ElggSiteTest();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		unset($this->site);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		parent::__destruct();
	}

	public function testElggSiteConstructor() {
		$attributes = array();
		$attributes['guid'] = null;
		$attributes['type'] = 'site';
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
		$attributes['description'] = null;
		$attributes['url'] = null;
		ksort($attributes);

		$entity_attributes = $this->site->expose_attributes();
		ksort($entity_attributes);

		$this->assertIdentical($entity_attributes, $attributes);
	}

	public function testElggSiteSaveAndDelete() {
		$guid = $this->site->save();
		$this->assertIsA($guid, 'int');
		$this->assertTrue($guid > 0);
		$this->assertIdentical(true, $this->site->delete());
	}

	public function testElggSiteGetUrl() {
		$this->site->url = 'http://example.com/';
		$this->assertIdentical($this->site->getURL(), 'http://example.com/');
	}
}

class ElggSiteTest extends \ElggSite {
	public function expose_attributes() {
		return $this->attributes;
	}
}
