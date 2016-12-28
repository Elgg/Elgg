<?php

namespace Elgg\Application;

class ServeFileHandlerTest extends \Elgg\TestCase {

	/**
	 * @var ServeFileHandler
	 */
	protected $handler;

	/**
	 * @var \ElggFile
	 */
	protected $file;

	public function setUp() {
		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$this->handler = _elgg_services()->serveFileHandler;

		$file = new \ElggFile();
		$file->owner_guid = 1;

		// Using special characters to test against files that have been
		// uploaded prior to implementation of filename sanitization
		// See #10608
		$file->setFilename("Iñtërn'âtiônàl-izætiøn.txt");
		$file->open('write');
		$file->write('Test file!');
		$file->close();

		$this->file = $file;
	}

	public function tearDown() {
		$this->file->delete();
	}

	function createRequest(\Elgg\FileService\File $file) {
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
	public function testSends403OnFileModificationTimeMismatch() {

		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);

		$request = $this->createRequest($file);

		$response = $this->handler->getResponse($request);
		$this->assertEquals(200, $response->getStatusCode());

		// Consecutive request will be send by the browswer with an etag header
		// We need to make sure we check modified time before issuing a Not Modified header
		// See issue #9571
		$request->headers->set('if_none_match', '"' . $this->file->getModifiedTime() . '"');

		sleep(1); // sometimes tests are too fast
		$this->file->setModifiedTime();

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

		$this->assertEquals('"' . $this->file->getModifiedTime() . '"', $response->headers->get('Etag'));
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

		$this->assertEquals('"' . $this->file->getModifiedTime() . '"', $response->headers->get('Etag'));
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

	/**
	 * @group FileService
	 */
	public function testSends304WithIfNoneMatchHeadersIncluded() {
		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);

		$request = $this->createRequest($file);
		$request->headers->set('if_none_match', '"' . $this->file->getModifiedTime() . '"');

		$response = $this->handler->getResponse($request);
		$this->assertEquals(304, $response->getStatusCode());
	}

	/**
	 * @group FileService
	 */
	public function testSends304WithIfNoneMatchHeadersIncludedAndDeflationEnabled() {
		$file = new \Elgg\FileService\File();
		$file->setFile($this->file);

		$request = $this->createRequest($file);
		$request->headers->set('if_none_match', '"' . $this->file->getModifiedTime() . '-gzip"');

		$response = $this->handler->getResponse($request);
		$this->assertEquals(304, $response->getStatusCode());
	}

}
