<?php
namespace Elgg\Http;

use PHPUnit_Framework_TestCase as TestCase;

class WebAppManifestResourceTest extends TestCase {

	public function testPagesExposeARelManifestLink() {
		$this->markTestIncomplete();
		// 1. Load any HTML page (e.g. the homepage)
		// 2. Find an element matching "link[rel=manifest]"
		//    - Assert that such an element exists
		//    - Assert that it has an href value
		// 3. Fetch the resource at the given href
		//    - Assert that the resource is a valid web app manifest
	}

}