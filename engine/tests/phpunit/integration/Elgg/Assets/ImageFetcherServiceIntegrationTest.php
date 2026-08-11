<?php

namespace Elgg\Assets;

class ImageFetcherServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	protected ?ImageFetcherService $service = null;
	
	public function up() {
		$this->service = _elgg_services()->imageFetcher;
	}
	
	public function testGetImage() {
		// need to use a real life url. Testing environment may not have a working localhost url present
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';

		// ensure empty cache
		$cache_key = $this->invokeInaccessableMethod($this->service, 'makeCacheKey', $image_url);
		elgg_delete_system_cache($cache_key);
		$this->assertNull(elgg_load_system_cache($cache_key));
		
		// fetch image
		$image = $this->service->getImage($image_url);
		$this->assertIsArray($image);
				
		// verify fetched image
		$this->assertNotEmpty($image['data']);
		$this->assertNotEmpty($image['content-type']);
		$this->assertNotEmpty($image['name']);
		
		// verify cache contains image
		$this->assertEquals($image, elgg_load_system_cache($cache_key));
	}
	
	public function testGetImageWithNonImage() {
		// need to use a real life url. Testing environment may not have a working localhost url present
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/bb8ca0f7bb5d11bf57d0cb7c19f310f7055b2128/CONTRIBUTING.md';

		// ensure empty cache
		$cache_key = $this->invokeInaccessableMethod($this->service, 'makeCacheKey', $image_url);
		elgg_delete_system_cache($cache_key);
		$this->assertNull(elgg_load_system_cache($cache_key));
		
		// fetch image
		$this->assertFalse($this->service->getImage($image_url));
		
		// verify cache contains a failure
		$this->assertFalse(elgg_load_system_cache($cache_key));
	}
}
