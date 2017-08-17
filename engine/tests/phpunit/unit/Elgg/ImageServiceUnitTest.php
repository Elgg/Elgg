<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class ImageServiceUnitTest extends \Elgg\UnitTestCase {
	
	private $image_service;
	
	private $temp_dir;
	private $temp_source_image_location;
	private $temp_destination_image_location;
	
	private $default_image_resize_params;

	public function up() {
		$this->image_service = _elgg_services()->imageService;

		$this->temp_dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		$this->temp_source_image_location = tempnam($this->temp_dir, 'imageservice');
		$source_image = _elgg_config()->dataroot . '1/1/300x300.jpg';
		file_put_contents($this->temp_source_image_location, file_get_contents($source_image));

		$this->temp_destination_image_location = $this->temp_dir . '200x200.jpg';

		$this->default_image_resize_params = [
			'w' => '200',
			'h' => '200',
			'upscale' => false,
			'square' => true,
		];
	}

	public function down() {
		if (file_exists($this->temp_source_image_location)) {
			$this->assertTrue(unlink($this->temp_source_image_location));
		}
		$this->assertFileNotExists($this->temp_source_image_location);

		if (file_exists($this->temp_destination_image_location)) {
			$this->assertTrue(unlink($this->temp_destination_image_location));
		}
		$this->assertFileNotExists($this->temp_destination_image_location);
	}

	public function testResizeFromImageExtension() {
		
		$source_image = _elgg_config()->dataroot . '1/1/300x300.jpg';
		$destination_image = $this->temp_destination_image_location;
		$params = $this->default_image_resize_params;
		
		$resize_result = $this->image_service->resize($source_image, $destination_image, $params);
		
		$this->assertTrue($resize_result);
		$this->assertFileExists($destination_image);
	}
	
	public function testResizeFromTmpExtension() {
		
		$source_image = $this->temp_source_image_location;
		$destination_image = $this->temp_destination_image_location;
		$params = $this->default_image_resize_params;
		
		$resize_result = $this->image_service->resize($source_image, $destination_image, $params);
		
		$this->assertTrue($resize_result);
		$this->assertFileExists($destination_image);
	}
	
	public function testResizeFromNoExtension() {
		
		$source_image = $this->temp_dir . 'image_with_no_extension';
		file_put_contents($source_image, file_get_contents($this->temp_source_image_location));
		
		$destination_image = $this->temp_destination_image_location;
		$params = $this->default_image_resize_params;
		
		$resize_result = $this->image_service->resize($source_image, $destination_image, $params);
		
		$this->assertTrue($resize_result);
		$this->assertFileExists($destination_image);
	}
}
