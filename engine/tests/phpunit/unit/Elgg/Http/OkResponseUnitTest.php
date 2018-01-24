<?php

namespace Elgg\Http;

use ElggObject;
use InvalidArgumentException;
use stdClass;

/**
 * @group HttpService
 * @group UnitTests
 */
class OkResponseUnitTest extends \Elgg\UnitTestCase {

	public $class;

	public function up() {
		$this->class = OkResponse::class;
	}

	public function down() {

	}

	public function testCanConstructWihtoutArguments() {
		$test_class = $this->class;
		$response = new $test_class();
		$this->assertEquals('', $response->getContent());
		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
		$this->assertEquals(null, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	public function testCanConstructWithArguments() {
		$content = 'foo';
		$status_code = ELGG_HTTP_PARTIAL_CONTENT;
		$forward_url = REFERRER;

		$test_class = $this->class;
		$response = new $test_class($content, $status_code, $forward_url);

		$this->assertEquals($content, $response->getContent());
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($forward_url, $response->getForwardURL());
		$this->assertEquals([], $response->getHeaders());
	}

	/**
	 * @dataProvider validContentValuesProvider
	 */
	public function testCanSetContent($value) {

		$test_class = $this->class;
		$response = new $test_class();

		$response->setContent($value);
		$this->assertEquals($value, $response->getContent());
	}

	public function validContentValuesProvider() {
		return [
			['foo'],
			[['foo' => 'bar']],
			[json_encode(['foo' => 'bar'])],
			[5],
			[-5],
			[5.5],
			[true],
			[false],
			[null],
		];
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider invalidContentValuesProvider
	 */
	public function testThrowsExceptionForInvalidContent($value) {

		$test_class = $this->class;
		$response = new $test_class();
		$response->setContent($value);
	}

	public function invalidContentValuesProvider() {
		self::createApplication();

		return [
			[new stdClass()],
			[(object) ['foo' => 'bar']],
			[
				function () {

				}
			]
		];
	}

	/**
	 * @dataProvider validStatusCodesProvider
	 */
	public function testCanSetStatusCode($value) {

		$test_class = $this->class;
		$response = new $test_class();

		$response->setStatusCode($value);
		$this->assertEquals($value, $response->getStatusCode());
	}

	public function validStatusCodesProvider() {
		return [
			[100],
			[200],
			['200'],
			[599],
			[null],
		];
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider invalidStatusCodesProvider
	 */
	public function testThrowsExceptionForInvalidStatusCodes($value) {

		$test_class = $this->class;
		$response = new $test_class();
		$response->setStatusCode($value);
	}

	public function invalidStatusCodesProvider() {
		return [
			[true],
			[false],
			['99'],
			[-200],
			['-200'],
			[600],
			[-1],
			['foo'],
			[[200]],
			[new stdClass()],
			[(object) 200],
		];
	}


	/**
	 * @dataProvider validForwardURLsProvider
	 */
	public function testCanSetForwardURL($value) {

		$test_class = $this->class;
		$response = new $test_class();

		$response->setForwardURL($value);
		$this->assertEquals($value, $response->getForwardURL());
	}

	public function validForwardURLsProvider() {
		return [
			[-1],
			[REFERRER],
			[REFERER],
			['foo'],
			['/foo'],
			['http://localhost/'],
			['?foo=bar'],
			[null],
		];
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider invalidForwardURLsProvider
	 */
	public function testThrowsExceptionForInvalidForwardURLs($value) {

		$test_class = $this->class;
		$response = new $test_class();
		$response->setForwardURL($value);
	}

	public function invalidForwardURLsProvider() {
		return [
			[true],
			[false],
			[200],
			[-2],
			[['url' => '/forward']],
		];
	}

	public function testCanSetHeaders() {

		$response = new $this->class();
		$this->assertEquals([], $response->getHeaders());

		$response->setHeaders(['Content-Type' => 'application/json']);
		$this->assertEquals(['Content-Type' => 'application/json'], $response->getHeaders());
	}

	/**
	 * @dataProvider statusCodesProvider
	 */
	public function testCanResolveStatusCodes($code, $status) {

		$test_class = $this->class;
		$response = new $test_class;
		$response->setStatusCode($code);

		$this->assertEquals($status[0], $response->isInformational());
		$this->assertEquals($status[1], $response->isSuccessful());
		$this->assertEquals($status[2], $response->isOk());
		$this->assertEquals($status[3], $response->isRedirection());
		$this->assertEquals($status[4], $response->isClientError());
		$this->assertEquals($status[5], $response->isServerError());
		$this->assertEquals($status[6], $response->isNotModified());
	}

	public function statusCodesProvider() {

		$codes = [];
		foreach (range(100, 599) as $code) {
			$codes[] = [
				$code,
				[
					$code >= 100 && $code <= 199,
					// isInformational
					$code >= 200 && $code <= 299,
					// isSuccessful
					$code == 200,
					// isOk
					in_array($code, [
						201,
						301,
						302,
						303,
						307,
						308
					]),
					// isRedirection
					$code >= 400 && $code <= 499,
					// isClientError
					$code >= 500 && $code <= 599,
					// isServerError
					$code == 304,
					// isNotModified
				]
			];
		}

		return $codes;
	}

}
