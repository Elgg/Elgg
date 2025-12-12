<?php

namespace Elgg\Filesystem;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

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

	#[DataProvider('validFilenameProvider')]
	public function testGetMimeTypeFromValidFile($filename, $expected) {
		$this->assertEquals($expected, $this->service->getMimeType($filename));
	}
	
	public static function validFilenameProvider() {
		return [
			[self::normalizeTestFilePath('dataroot/1/1/300x300.jpg'), 'image/jpeg'],
			[self::normalizeTestFilePath('dataroot/1/1/400x300.gif'), 'image/gif'],
			[self::normalizeTestFilePath('dataroot/1/1/foobar.txt'), 'text/plain'],
		];
	}
	
	public function testGetMimeTypeWithInvalidFileCausesException() {
		$this->expectException(InvalidArgumentException::class);
		$this->service->getMimeType($this->normalizeTestFilePath('file_not_found.txt'));
	}
	
	public function testGetMimeTypeFiresEvent() {
		$calls = 0;
		$value = null;
		$handler = function(\Elgg\Event $event) use (&$calls, &$value) {
			$calls++;
			$value = $event->getValue();
		};
		elgg_register_event_handler('mime_type', 'file', $handler);
		
		$mimetype = $this->service->getMimeType($this->normalizeTestFilePath('dataroot/1/1/300x300.jpg'));
		
		$this->assertEquals(1, $calls);
		$this->assertEquals('image/jpeg', $mimetype);
		$this->assertEquals($mimetype, $value);
		
		elgg_unregister_event_handler('mime_type', 'file', $handler);
	}
	
	public function testGetMimeTypeFromUnknownFileType() {
		$this->assertNotEmpty($this->service->getMimeType($this->normalizeTestFilePath('.gitignore')));
	}
	
	public function testGetMimeTypeFromUnknownFileTypeWithCustomDefault() {
		$this->markTestSkipped("Don't know how to generate a file which results in an unknow mimetype");
	}

	#[DataProvider('getSimpleTypeProvider')]
	public function testGetSimpleType($mimetype, $expected) {
		$this->assertEquals($expected, $this->service->getSimpleType($mimetype));
	}
	
	public static function getSimpleTypeProvider() {
		return [
			['text/html', 'document'],
			['image/jpg', 'image'],
			['application/msword', 'document'],
			['application/ogg', 'audio'],
			['something/unknown', 'general'],
		];
	}
	
	public function testGetSimpleTypeFiresEvent() {
		$calls = 0;
		$value = null;
		$handler = function(\Elgg\Event $event) use (&$calls, &$value) {
			$calls++;
			$value = $event->getValue();
		};
		elgg_register_event_handler('simple_type', 'file', $handler);
		
		$simpletype = $this->service->getSimpleType('image/jpeg');
		
		$this->assertEquals(1, $calls);
		$this->assertEquals('image', $simpletype);
		$this->assertEquals($simpletype, $value);
		
		elgg_unregister_event_handler('simple_type', 'file', $handler);
	}

	#[DataProvider('validSimpleTypeFilenameProvider')]
	public function testGetSimpleTypeFromFile($filename, $expected) {
		$this->assertEquals($expected, $this->service->getSimpleTypeFromFile($filename));
	}
	
	public static function validSimpleTypeFilenameProvider() {
		return [
			[self::normalizeTestFilePath('dataroot/1/1/300x300.jpg'), 'image'],
			[self::normalizeTestFilePath('dataroot/1/1/400x300.gif'), 'image'],
			[self::normalizeTestFilePath('dataroot/1/1/foobar.txt'), 'document'],
		];
	}
}
