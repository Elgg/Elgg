<?php

namespace Elgg\Filesystem;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

/**
 * @group UnitTests
 */
class MimeTypeServiceUnitTest extends UnitTestCase {

	/**
	 * @var MimeTypeService
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->mimetype;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	/**
	 * @dataProvider validFilenameProvider
	 */
	public function testGetMimeTypeFromValidFile($filename, $expected) {
		$this->assertEquals($expected, $this->service->getMimeType($filename));
	}
	
	public function validFilenameProvider() {
		return [
			[$this->normalizeTestFilePath('dataroot/1/1/300x300.jpg'), 'image/jpeg'],
			[$this->normalizeTestFilePath('dataroot/1/1/400x300.gif'), 'image/gif'],
			[$this->normalizeTestFilePath('dataroot/1/1/foobar.txt'), 'text/plain'],
		];
	}
	
	public function testGetMimeTypeWithInvalidFileCausesException() {
		$this->expectException(InvalidArgumentException::class);
		$this->service->getMimeType($this->normalizeTestFilePath('file_not_found.txt'));
	}
	
	public function testGetMimeTypeFiresHook() {
		$calls = 0;
		$value = null;
		$handler = function(\Elgg\Hook $hook) use (&$calls, &$value) {
			$calls++;
			$value = $hook->getValue();
		};
		elgg_register_plugin_hook_handler('mime_type', 'file', $handler);
		
		$mimetype = $this->service->getMimeType($this->normalizeTestFilePath('dataroot/1/1/300x300.jpg'));
		
		$this->assertEquals(1, $calls);
		$this->assertEquals('image/jpeg', $mimetype);
		$this->assertEquals($mimetype, $value);
		
		elgg_unregister_plugin_hook_handler('mime_type', 'file', $handler);
	}
	
	public function testGetMimeTypeFromUnknownFileType() {
		$this->assertNotEmpty($this->service->getMimeType($this->normalizeTestFilePath('.gitignore')));
	}
	
	public function testGetMimeTypeFromUnknownFileTypeWithCustomDefault() {
		$this->markTestSkipped("Don't know how to generate a file which results in an unknow mimetype");
	}
	
	/**
	 * @dataProvider getSimpleTypeProvider
	 */
	public function testGetSimpleType($mimetype, $expected) {
		$this->assertEquals($expected, $this->service->getSimpleType($mimetype));
	}
	
	public function getSimpleTypeProvider() {
		return [
			['text/html', 'document'],
			['image/jpg', 'image'],
			['application/msword', 'document'],
			['application/ogg', 'audio'],
			['something/unknown', 'general'],
		];
	}
	
	public function testGetSimpleTypeFiresHook() {
		$calls = 0;
		$value = null;
		$handler = function(\Elgg\Hook $hook) use (&$calls, &$value) {
			$calls++;
			$value = $hook->getValue();
		};
		elgg_register_plugin_hook_handler('simple_type', 'file', $handler);
		
		$simpletype = $this->service->getSimpleType('image/jpeg');
		
		$this->assertEquals(1, $calls);
		$this->assertEquals('image', $simpletype);
		$this->assertEquals($simpletype, $value);
		
		elgg_unregister_plugin_hook_handler('simple_type', 'file', $handler);
	}
	
	/**
	 * @dataProvider validSimpleTypeFilenameProvider
	 */
	public function testGetSimpleTypeFromFile($filename, $expected) {
		$this->assertEquals($expected, $this->service->getSimpleTypeFromFile($filename));
	}
	
	public function validSimpleTypeFilenameProvider() {
		return [
			[$this->normalizeTestFilePath('dataroot/1/1/300x300.jpg'), 'image'],
			[$this->normalizeTestFilePath('dataroot/1/1/400x300.gif'), 'image'],
			[$this->normalizeTestFilePath('dataroot/1/1/foobar.txt'), 'document'],
		];
	}
}
