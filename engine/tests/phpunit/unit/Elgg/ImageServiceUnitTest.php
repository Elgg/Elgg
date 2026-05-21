<?php

namespace Elgg;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\RangeException;

class ImageServiceUnitTest extends \Elgg\UnitTestCase {
	
	private $image_service;
	
	private $temp_dir;
	private $temp_source_image_location;
	private $temp_destination_image_location;
	
	private $default_image_resize_params;
	
	protected int $config_image_height;
	protected int $config_image_width;
	protected int $config_image_resolution;

	public function up() {
		$this->image_service = _elgg_services()->imageService;

		$this->temp_dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		$this->temp_source_image_location = tempnam($this->temp_dir, 'imageservice');
		$source_image = _elgg_services()->config->dataroot . '1/1/300x300.jpg';
		file_put_contents($this->temp_source_image_location, file_get_contents($source_image));

		$this->temp_destination_image_location = $this->temp_dir . '200x200.jpg';

		$this->default_image_resize_params = [
			'w' => '200',
			'h' => '200',
			'upscale' => false,
			'square' => true,
		];
		
		$this->config_image_height = _elgg_services()->config->image_resize_max_height;
		$this->config_image_width = _elgg_services()->config->image_resize_max_width;
		$this->config_image_resolution = _elgg_services()->config->image_resize_max_resolution;
	}

	public function down() {
		if (file_exists($this->temp_source_image_location)) {
			$this->assertTrue(unlink($this->temp_source_image_location));
		}
		$this->assertFileDoesNotExist($this->temp_source_image_location);

		if (file_exists($this->temp_destination_image_location)) {
			$this->assertTrue(unlink($this->temp_destination_image_location));
		}
		$this->assertFileDoesNotExist($this->temp_destination_image_location);
		
		_elgg_services()->config->image_resize_max_height = $this->config_image_height;
		_elgg_services()->config->image_resize_max_width = $this->config_image_width;
		_elgg_services()->config->image_resize_max_resolution = $this->config_image_resolution;
	}

	public function testResizeFromImageExtension() {
		
		$source_image = _elgg_services()->config->dataroot . '1/1/300x300.jpg';
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
	
	public function testAssertValidImageDimensionsWithNonImage() {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessageMatches('/Unable to read image data for \'.+\'/');
		$this->invokeInaccessableMethod($this->image_service, 'assertValidImageDimensions', elgg_get_data_path() . '1/1/foobar.txt');
	}
	
	public function testAssertValidImageDimensionsWithTooHighImage() {
		_elgg_services()->config->image_resize_max_height = 100;
		
		$this->expectException(RangeException::class);
		$this->expectExceptionMessage('Image height too large to resize');
		$this->invokeInaccessableMethod($this->image_service, 'assertValidImageDimensions', $this->temp_source_image_location);
	}
	
	public function testAssertValidImageDimensionsWithTooWideImage() {
		_elgg_services()->config->image_resize_max_width = 100;
		
		$this->expectException(RangeException::class);
		$this->expectExceptionMessage('Image width too large to resize');
		$this->invokeInaccessableMethod($this->image_service, 'assertValidImageDimensions', $this->temp_source_image_location);
	}
	
	public function testAssertValidImageDimensionsWithTooHighResolutionImage() {
		_elgg_services()->config->image_resize_max_resolution = 100;
		
		$this->expectException(RangeException::class);
		$this->expectExceptionMessage('Image resolution too large to resize');
		$this->invokeInaccessableMethod($this->image_service, 'assertValidImageDimensions', $this->temp_source_image_location);
	}

	public function testAssertValidImageDimensionsWithValidImage() {
		_elgg_services()->config->image_resize_max_height = 300;
		_elgg_services()->config->image_resize_max_width = 300;
		_elgg_services()->config->image_resize_max_resolution = 300 * 300;
		
		$this->invokeInaccessableMethod($this->image_service, 'assertValidImageDimensions', $this->temp_source_image_location);
	}
}
