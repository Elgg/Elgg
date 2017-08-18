<?php

namespace Elgg\Integration;

/**
 * Elgg Test \ElggSite
 *
 * @group IntegrationTests
 */
class ElggCoreSiteTest extends \Elgg\LegacyIntegrationTestCase {

	/**
	 * @var ElggSiteWithExposableAttributes
	 */
	public $site;

	public function up() {
		$this->site = new ElggSiteWithExposableAttributes();
	}

	public function down() {
		unset($this->site);
	}

	public function testElggSiteConstructor() {
		$attributes = [];
		$attributes['guid'] = null;
		$attributes['type'] = 'site';
		$attributes['subtype'] = null;
		$attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$attributes['container_guid'] = elgg_get_logged_in_user_guid();
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

	public function testElggSiteGetUrl() {
		$this->assertIdentical($this->site->getURL(), elgg_get_site_url());
	}
}

class ElggSiteWithExposableAttributes extends \ElggSite {
	public function expose_attributes() {
		return $this->attributes;
	}
}
