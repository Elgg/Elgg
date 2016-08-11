<?php

namespace Elgg\Application;

class CacheHandlerTest extends \Elgg\TestCase {

	/**
	 * @var CacheHandler
	 */
	protected $handler;

	public function setUp() {
		$app = elgg();
		$config = _elgg_services()->config;
		$this->handler = new CacheHandler($app, $config, []);
	}

	protected function _testParseFail($input) {
		$this->assertEquals(array(), $this->handler->parsePath($input));
	}

	public function testCanParseValidRequest() {
		$this->assertEquals(array(
			'ts' => '1234',
			'viewtype' => 'default',
			'view' => 'hel/8lo-wo_rl.d.js',
				), $this->handler->parsePath('/cache/1234/default/hel/8lo-wo_rl.d.js'));
	}

	public function testCantParseDoubleDot() {
		$this->_testParseFail('/cache/1234/default/hel/8lo-wo_rl..d.js');
	}

	public function testParserRequiresNumericTs() {
		$this->_testParseFail('/cache/a1234/default/hel/8lo-wo_rl.d.js');
	}

	public function testParserRequiresNonEmptyViewtype() {
		$this->_testParseFail('/cache/1234//hel/8lo-wo_rl.d.js');
	}

	public function testParserHasCharWhitelist() {
		$this->_testParseFail('/cache/a1234/default/he~l/8lo-wo_rl.d.js');
	}

	public function testParseRequiresLeadingSlash() {
		$this->_testParseFail('cache/1234/default/hello/world');
	}

	public function testCanHandleConditionalRequests() {
		$this->markTestIncomplete();
	}

	public function testGetViewFileTypeHandlesJs() {
		$this->markTestIncomplete("getViewFileType() is private/protected");

		$type = $this->handler->getViewFileType('js/some/view.js');

		$this->assertEquals('js', $type);
	}

	public function testGetContentTypeHandlesJs() {
		$this->markTestIncomplete("getContentType() is private/protected");

		$mediaType = $this->handler->getContentType('view.js');

		$this->assertEquals('application/javascript', $mediaType);
	}

}
