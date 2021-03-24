<?php

namespace Elgg\Assets;

class ImageFetcherServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testGetImage() {
		$fetcher = _elgg_services()->imageFetcher;
		
		// need to use a real life url. Testing environment may not have a working localhost url present
		$image_url = 'https://raw.githubusercontent.com/Elgg/Elgg/70c2f4535af7b67b690617ebeba74fc59a2b55d2/engine/tests/test_files/dataroot/1/1/300x300.jpg';

		//verify empty cache
		$this->assertNull(elgg_load_system_cache('image_fetcher_' . md5($image_url)));
		
		// fetch image
		$image = $fetcher->getImage($image_url);
		$this->assertIsArray($image);
				
		// verify fetched image
		$this->assertNotEmpty($image['data']);
		$this->assertNotEmpty($image['content-type']);
		$this->assertNotEmpty($image['name']);
		
		// verify cache contains image
		$this->assertEquals($image, elgg_load_system_cache('image_fetcher_' . md5($image_url)));
	}

}
