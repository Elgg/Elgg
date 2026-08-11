<?php

namespace Elgg\Assets;

use Elgg\UnitTestCase;

class ImageFetcherServiceUnitTest extends UnitTestCase {
	
	protected ?ImageFetcherService $service = null;
	
	public function up() {
		$this->service = _elgg_services()->imageFetcher;
	}
	
	public function testValidateImageDataWithValidImage() {
		$source_image = _elgg_services()->config->dataroot . '1/1/300x300.jpg';
		
		$this->assertFileExists($source_image);
		$contents = file_get_contents($source_image);
		
		$this->assertTrue($this->invokeInaccessableMethod($this->service, 'validateImageData', $contents));
	}
	
	public function testValidateImageDataWithInvalidImage() {
		$source_image = _elgg_services()->config->dataroot . '1/1/foobar.txt';
		
		$this->assertFileExists($source_image);
		$contents = file_get_contents($source_image);
		
		$this->assertFalse($this->invokeInaccessableMethod($this->service, 'validateImageData', $contents));
	}
}
