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

		$this->handler = _elgg_services()->serveFileHandler;

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
		$request = \Elgg\Http\Request::create("?$path_key=$path");

		$cookie_name = _elgg_services()->config->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);
		return $request;
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

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);
		$file->bindSession(true);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals(200, $response->getStatusCode());

		_elgg_services()->session->invalidate();
		$cookie_name = _elgg_services()->config->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);

		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testResponseHeadersMatchFileAttributesForInlineUrls() {
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
