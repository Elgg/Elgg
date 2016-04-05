<?php

class ElggFileTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \ElggFile
	 */
	protected $file;

	protected function setUp() {

		_elgg_filestore_init();
		
		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename("foobar.txt");

		$this->file = $file;
	}

	/**
	 * @group FileService
	 */
	public function testCanSetModifiedTime() {
		$time = $this->file->getModifiedTime();
		$this->file->setModifiedTime();
		$this->assertNotEquals($time, $this->file->getModifiedTime());
	}

	/**
	 * @group FileService
	 */
	public function testCanSetMimeType() {
		unset($this->file->mimetype);

		$mimetype = 'application/plain';
		$this->file->setMimeType($mimetype);
		$this->assertEquals($mimetype, $this->file->getMimeType());
	}

	/**
	 * @group FileService
	 */
	public function testCanDetectMimeType() {
		$mime = $this->file->detectMimeType(null, 'text/plain');

		// mime should not be null if default is set
		$this->assertNotNull($mime);

		// mime of a file object should match mime of a file path that represents this file on filestore
		$resource_mime = $this->file->detectMimeType($this->file->getFilenameOnFilestore(), 'text/plain');
		$this->assertEquals($mime, $resource_mime);

		// calling detectMimeType statically raises strict policy warning
		// @todo: remove this once a new static method has been implemented
		error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

		// method output should not differ between a static and a concrete call if the file path is set
		$resource_mime_static = \ElggFile::detectMimeType($this->file->getFilenameOnFilestore(), 'text/plain');
		$this->assertEquals($resource_mime, $resource_mime_static);
	}

	/**
	 * @group FileService
	 * @dataProvider providerSimpleTypeMap
	 */
	public function testCanParseSimpleType($mime_type, $simple_type) {
		unset($this->file->simpletype);
		$this->file->mimetype = $mime_type;
		$this->assertEquals($simple_type, $this->file->getSimpleType());
	}

	function providerSimpleTypeMap() {
		return array(
			array('x-world/x-svr' , 'general'),
			array('application/msword', 'document'),
			array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'document'),
			array('application/vnd.oasis.opendocument.text', 'document'),
			array('application/pdf', 'document'),
			array('application/ogg', 'audio'),
			array('text/css', 'document'),
			array('text/plain', 'document'),
			array('audio/midi', 'audio'),
			array('audio/mpeg', 'audio'),
			array('image/jpeg', 'image'),
			array('image/bmp', 'image'),
			array('video/mpeg', 'video'),
			array('video/quicktime', 'video'),
		);
	}
}