<?php

namespace Elgg\Application;

/**
 * @group UnitTests
 */
class ServeFileHandlerUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var ServeFileHandler
	 */
	protected $handler;
	
	/**
	 * @var \ElggFile
	 */
	protected $file;

	public function up() {
		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$this->handler = _elgg_services()->serveFileHandler;
	}

	public function down() {
		if (isset($this->file)) {
			$this->file->delete();
		}
	}

	function createRequest(\Elgg\FileService\File $file) {
		$site_url = elgg_get_site_url();
		$url = $file->getURL();
		$path = substr($url, strlen($site_url));
		$request = \Elgg\Http\Request::create("/$path");

		$cookie_name = _elgg_config()->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);

		return $request;
	}
	
	function fileProvider() {
		return [
			// Using special characters to test against files that have been
			// uploaded prior to implementation of filename sanitization
			// See #10608
			["Iñtërn'âtiônàl-izætiøn.txt"],
			// filename with spaces
			['a filename.txt'],
			// regular filename
			['filename.txt'],
		];
	}
	
	function createFile($filename) {
		$this->assertNotEmpty($filename);
		
		$file = new \ElggFile();
		$file->owner_guid = 1;
		
		// Using special characters to test against files that have been
		// uploaded prior to implementation of filename sanitization
		// See #10608
		$file->setFilename($filename);
		$file->open('write');
		$file->write('Test file!');
		$file->close();
		
		$this->assertTrue($file->exists());
		
		return $file;
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
	 * @dataProvider fileProvider
	 */
	public function testSend403OnUrlExpiration($filename) {

		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);
		$file->setExpires('-1 day');

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testSends403OnFileModificationTimeMismatch($filename) {

		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);

		$request = $this->createRequest($file);

		$response = $this->handler->getResponse($request);
		$this->assertEquals(200, $response->getStatusCode());

		// Consecutive request will be send by the browswer with an etag header
		// We need to make sure we check modified time before issuing a Not Modified header
		// See issue #9571
		$request->headers->set('if_none_match', '"' . $test_file->getModifiedTime() . '"');

		sleep(1); // sometimes tests are too fast
		$test_file->setModifiedTime();

		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testResponseCodesOnSessionRestartWithCookieEnabledForFileUrls($filename) {

		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);
		$file->bindSession(true);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals(200, $response->getStatusCode());

		_elgg_services()->session->invalidate();
		$cookie_name = _elgg_config()->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);

		$response = $this->handler->getResponse($request);
		$this->assertEquals(403, $response->getStatusCode());
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testResponseHeadersMatchFileAttributesForInlineUrls($filename) {
		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);
		$file->setDisposition('inline');
		$file->bindSession(false);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals('text/plain', $response->headers->get('Content-Type'));

		$filesize = filesize($test_file->getFilenameOnFilestore());
		$this->assertEquals($filesize, $response->headers->get('Content-Length'));

		$this->assertContains('inline', $response->headers->get('Content-Disposition'));

		$this->assertEquals('"' . $test_file->getModifiedTime() . '"', $response->headers->get('Etag'));
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testResponseHeadersMatchFileAttributesForAttachmentUrls($filename) {
		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);
		$file->setDisposition('attachment');
		$file->bindSession(true);

		$request = $this->createRequest($file);
		$response = $this->handler->getResponse($request);

		$this->assertEquals('text/plain', $response->headers->get('Content-Type'));

		$filesize = filesize($test_file->getFilenameOnFilestore());
		$this->assertEquals($filesize, $response->headers->get('Content-Length'));

		$this->assertContains('attachment', $response->headers->get('Content-Disposition'));

		$this->assertEquals('"' . $test_file->getModifiedTime() . '"', $response->headers->get('Etag'));
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidDisposition($filename) {
		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);
		$file->setDisposition('foo');
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testSends304WithIfNoneMatchHeadersIncluded($filename) {
		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);

		$request = $this->createRequest($file);
		$request->headers->set('if_none_match', '"' . $test_file->getModifiedTime() . '"');

		$response = $this->handler->getResponse($request);
		$this->assertEquals(304, $response->getStatusCode());
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

	/**
	 * @group FileService
	 * @dataProvider fileProvider
	 */
	public function testSends304WithIfNoneMatchHeadersIncludedAndDeflationEnabled($filename) {
		$test_file = $this->createFile($filename);
		$this->file = $test_file;
		
		$file = new \Elgg\FileService\File();
		$file->setFile($test_file);

		$request = $this->createRequest($file);
		$request->headers->set('if_none_match', '"' . $test_file->getModifiedTime() . '-gzip"');

		$response = $this->handler->getResponse($request);
		$this->assertEquals(304, $response->getStatusCode());
		
		$this->assertTrue($test_file->delete());
		unset($this->file);
	}

}
