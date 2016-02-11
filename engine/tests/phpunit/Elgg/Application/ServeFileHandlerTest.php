<?php

namespace Elgg\Application;

use PHPUnit_Framework_TestCase;

class ServeFileHandlerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ServeFileHandler
	 */
	protected $handler;

	/**
	 * @var \ElggFile
	 */
	protected $file;

	public function setUp() {
		$app = _elgg_testing_application();
		$dataroot = _elgg_testing_config()->getDataPath();

		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$site_secret_mock = $this->getMockBuilder('\Elgg\Database\SiteSecret')->getMock();
		$site_secret_mock->method('init')->willReturn('strongSiteSecret1234567890');
		_elgg_services()->setValue('siteSecret', $site_secret_mock);

		$this->handler = new ServeFileHandler($app);

		$file_mock = $this->getMockBuilder('\ElggFile')->disableOriginalConstructor()->getMock();
		$file_mock->method('getFileNameOnFilestore')->willReturn("{$dataroot}file_service/foobar.txt");
		$file_mock->method('exists')->willReturn(true);
		$this->file = $file_mock;
	}

	function createRequest($file) {
		$site_url = elgg_get_site_url();
		$url = $file->getURL();
		$path = substr($url, strlen($site_url));
		$path_key = \Elgg\Application::GET_PATH_KEY;
		return \Elgg\Http\Request::create("?$path_key=$path");
	}

	/**
	 * @group FileService
	 */
	public function testSend400WhenUrlIsMalformmatted() {

		$request = \Elgg\Http\Request::create('');
		$response = $this->handler->getResponse($request);
		$this->assertEquals(400, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testSend403OnUrlExpiration() {

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->setExpires('-1 day');

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testSends403OnFileModificationTimeMismatch () {
		$this->markTestSkipped('this test boots core');

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);

		$request = $this->createRequest($file);

		$response = $this->handler->getResponse($request);
		$this->assertEquals(200, $response->getStatusCode());

		touch($this->file->getFilenameOnFilestore());
		clearstatcache(true, $this->file->getFilenameOnFilestore());
		
		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testResponseCodesOnSessionRestartWithCookieEnabledForFileUrls() {
		$this->markTestSkipped('this test boots core');

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->bindSession(true);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals(200, $response->getStatusCode());

		_elgg_services()->session->invalidate();
		_elgg_services()->session->start();

		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testResponseHeadersMatchFileAttributesForInlineUrls() {
		$this->markTestSkipped('this test boots core');

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->setDisposition('inline');
		$file->bindSession(false);
		
		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);
		
		$this->assertEquals('text/plain', $response->headers->get('Content-Type'));

		$filesize = filesize($this->file->getFilenameOnFilestore());
		$this->assertEquals($filesize, $response->headers->get('Content-Length'));

		$this->assertContains('inline', $response->headers->get('Content-Disposition'));

		$this->assertEquals('"' . filemtime($this->file->getFilenameOnFilestore()) . '"', $response->headers->get('Etag'));
	}

	/**
	 * @group FileService
	 */
	public function testResponseHeadersMatchFileAttributesForAttachmentUrls() {
		$this->markTestSkipped('this test boots core');

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->setDisposition('attachment');
		$file->bindSession(true);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals('text/plain', $response->headers->get('Content-Type'));

		$filesize = filesize($this->file->getFilenameOnFilestore());
		$this->assertEquals($filesize, $response->headers->get('Content-Length'));

		$this->assertContains('attachment', $response->headers->get('Content-Disposition'));

		$this->assertEquals('"' . filemtime($this->file->getFilenameOnFilestore()) . '"', $response->headers->get('Etag'));
	}

	/**
	 * @group FileService
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidDisposition() {
		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->setDisposition('foo');
	}

}
